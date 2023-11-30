<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Aws\Ses\SesClient;

use App\Models\Asset;
use App\Models\Products;

function cacheImage(string $fileName, int $width = 0, int $height = 0, bool $webp = true):string {	
	$publicFileName = sprintf('%s%s.%s', 
		explode('.', $fileName)[0], 
		($width > 0 || $height > 0) ? sprintf('-%d-%d', $width, $height) : '',
		$webp ? 'webp' : explode('.', $fileName)[1]
	);

	if (!Storage::disk('public')->exists($publicFileName)) {
		$data = Storage::get($fileName);

		$manager = new ImageManager(['driver' => 'imagick']);
		$image = $manager->make($data);

		if ($width > 0 || $height > 0) {
			$image->resize($width == 0 ? null : $width, $height == 0 ? null : $height, function($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});
		}
		
		if($webp) {
			$image->encode('webp');
		}
		
		Storage::disk('public')->put($publicFileName, $image);
	}
		
	return Storage::disk('public')->url($publicFileName);
}

function cacheImages($records, int $width = 0, int $height = 0, bool $webp = true){
	foreach ($records as $i => $record) {
		if (property_exists($record, 'fileName')) {
			if (is_array($record)) {
				if (!empty($record['fileName'])) {
					$record['fileName'] = cacheImage($record['fileName'], $width, $height, $webp);
				}
			} else {
				if (!empty($record->fileName)) {
					$record->fileName = cacheImage($record->fileName, $width, $height, $webp);
				}
			}
		}
		
	}

	return $records;
}

function preloadImage(string $url) {
	if (session()->has('preloaded-images')) {
		$records = session()->get('preloaded-images');

		if (!in_array($url, $records)) {
			$records[] = $url;
			session()->put('preloaded-images', $records);
			session()->save();
		}

	} else {
		$records = [$url];
		session()->put('preloaded-images', $records);
		session()->save();
	}
}

function storeImages(Request $request, $id, string $type):array {
	$fileNames = [];

	foreach ($request->files as $i => $file) {
		$mimeType = str_replace('image/', '', $file->getClientMimeType());
		if ($mimeType == 'svg+xml') { $mimeType = 'svg'; }
		else if ($mimeType == 'jpeg') { $mimeType = 'jpg'; }

		$fileName = sprintf('%s-%s-%s-%s.%s', 
			$type,
			$id,
			$_SERVER['REQUEST_TIME'],
			rtrim(explode('.', str_replace([' ', '(', ')'], '-', $file->getClientOriginalName()))[0], '-'),
			$mimeType
		);

		Storage::put($fileName, file_get_contents($file));

		$asset = Asset::create([
			'fileName' => $file->getClientOriginalName(),
			'fileNameAWS' => $fileName,
		]);
		
		$fileNames[] = [
			'id' => $asset->id,
			'new' => $asset->fileNameAWS,
			'old' => $asset->fileName,
		];
	}

	return $fileNames;
}

// AWS S3
function connectSes() {
  $connection = new SesClient([
    'version' => 'latest',
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'profile' => 'default',
  ]);

  return $connection;
}

function getS3Url(array $records):array {
	foreach ($records as $i => $record) {
		if (property_exists($record, 'fileName')) {
			$record->fileName = Storage::disk('s3')->url($record->fileName);
		}
	}

	return $records;
}

