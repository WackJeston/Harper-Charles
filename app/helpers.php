<?php

use Aws\S3\S3Client;

function storeImages($request, int $id, string $type):array {
	$request->validate([
		'name' => 'max:100',
		'image' => 'required|mimes:jpg,jpeg,png,svg',
	]);

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

		$fileName = str_replace(['----', '---', '--'], '-', $fileName);

		$file->move('assets', $fileName);
		uploadS3($fileName);

		$fileNames[] = [
			'new' => $fileName,
			'old' => $file->getClientOriginalName()
		];
	}

	return $fileNames;
}

// AWS S3
function connectS3() {
  $connection = new S3Client([
    'version' => 'latest',
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'profile' => 'default',
  ]);

  return $connection;
}

function uploadS3(string $fileName, string $body = '') {
  $aws = connectS3();

  if ($body != '') {
		Storage::disk('local')->put($fileName, $body);
  }

	$aws->putObject([
		'Bucket' => $_ENV['AWS_BUCKET'],
		'Key' => 'assets/' . $fileName,
		'SourceFile' => 'assets/' . $fileName,
	]);

	File::delete(public_path() . "/assets/" . $fileName);
}

function deleteS3(string $fileName) {
  $aws = connectS3();

  $aws->deleteObject([
    'Bucket' => $_ENV['AWS_BUCKET'],
    'Key' => 'assets/' . $fileName,
  ]);
}

