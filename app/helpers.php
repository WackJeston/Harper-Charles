<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Aws\Ses\SesClient;

use App\Models\Asset;
use App\Models\Products;

function resetShowMarker() {
	if (explode('?', url()->current())[0] != explode('?', session()->get('_previous')['url'])[0]) {
		session()->put('pageShowMarker', false);
	}
}

function cacheImage(string $fileName, int $width = 0, int $height = 0, bool $webp = true):string {	
	$publicFileName = sprintf('%s%s.%s', 
		explode('.', $fileName)[0], 
		($width > 0 || $height > 0) ? sprintf('-%d-%d', $width, $height) : '',
		$webp ? 'webp' : explode('.', $fileName)[1]
	);

	if (!Storage::disk('public')->exists($publicFileName)) {
		$data = Storage::get($fileName);

		if (!empty($data)) {
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
			ImageOptimizer::optimize(env('ASSET_PATH_SERVER') . $publicFileName);
		}
	}
		
	return Storage::disk('public')->url($publicFileName);
}

function cacheImages($records, int $width = 0, int $height = 0, bool $webp = true) {
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

function preloadImage(string $url, bool $first = false) {
	if (session()->has('preloaded-images')) {
		if ($first) {
			$records = [];
		} else {
			$records = session()->get('preloaded-images');
		}

		if (!in_array($url, $records)) {
			$records[] = $url;
			session()->put('preloaded-images', $records);
		}

	} else {
		$records = [$url];
		session()->put('preloaded-images', $records);
	}
}

function storeImages(Request $request, $id, string $type):array {
	$fileNames = [];

	if (!is_array($request->file('image'))) {
		$temp = $request->file('image');
		$array = [];

		$array[] = $request->file('image');

	} else {
		$array = $request->file('image');
	}

	foreach ($array as $i => $file) {
		$mimeType = explode('.', $file->getClientOriginalName())[1];

		$fileName = sprintf('%s-%s-%s-%s.%s',
			$type,
			$id,
			$_SERVER['REQUEST_TIME'],
			rtrim(explode('.', str_replace([' ', '(', ')'], '-', $file->getClientOriginalName()))[0], '-'),
			$mimeType
		);

		Storage::put($fileName, file_get_contents($file));

		$asset = Asset::create([
			'name' => $file->getClientOriginalName(),
			'fileName' => $fileName,
		]);
		
		$fileNames[] = [
			'id' => $asset->id,
			'new' => $asset->fileName,
			'old' => $asset->name,
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

