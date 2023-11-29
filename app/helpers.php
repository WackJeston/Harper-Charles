<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Aws\Ses\SesClient;

use App\Models\Products;

function cacheImage(string $fileName):string {	
	if (!Storage::disk('public')->exists($fileName)) {
		$data = Storage::get($fileName);
	
		$manager = new ImageManager(['driver' => 'imagick']);
		$image = $manager->make($data);
		
		Storage::disk('public')->put($fileName, $data);
	}
		
	return Storage::disk('public')->url($fileName);
}

function cacheImages($records){
	foreach ($records as $i => $record) {
		if (is_array($record)) {
			if (property_exists($record, 'fileName')) {
				$record['fileName'] = cacheImage($record['fileName']);
			}
		} else {
			if (property_exists($record, 'fileName')) {
				// dd(cacheImage($record->fileName));
				$record->fileName = cacheImage($record->fileName);
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

		$fileName = sprintf('%s-%s-%s-%s.webp', 
			$type,
			$id,
			$_SERVER['REQUEST_TIME'],
			rtrim(explode('.', str_replace([' ', '(', ')'], '-', $file->getClientOriginalName()))[0], '-')
		);

		$fileName = str_replace(['----', '---', '--'], '-', $fileName);

		if ($mimeType != 'webp') {
			$manager = new ImageManager(['driver' => 'imagick']);
			$data = $manager->make(file_get_contents($file))->encode('webp');
		} else {
			$data = file_get_contents($file);
		}

		Storage::put($fileName, $data);
		
		$fileNames[] = [
			'new' => $fileName,
			'old' => $file->getClientOriginalName()
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

