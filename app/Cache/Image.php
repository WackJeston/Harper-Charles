<?php

namespace App\Cache;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

// use App\Models\Product;
// 
// Product::create(
// 	[
// 		'title' => 'Testing',
// 		'productNumber' => 'hq930rh9qnvegnwog8w93',
// 		'price' => '100.00',
// 	]
// );

$filePathOld = url()->previous();
$fileName = end(explode('/', $filePathOld));
$filePathNew = env('ASSET_PATH') . $fileName;

$data = Storage::get($fileName);

$manager = new ImageManager(['driver' => 'imagick']);
$image = $intervention->make($data);

Storage::disk('local')->put($fileName, $data);

readfile(Storage::disk('local')->get($fileName));