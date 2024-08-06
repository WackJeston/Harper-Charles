<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Aws\Ses\SesClient;
use KlaviyoAPI\KlaviyoAPI;

use App\Models\Asset;
use App\Models\Products;
use App\Models\User;

function getCachedRecords(string $key) {
	if (Cache::has($key)) {
		return Cache::get($key);
	}

	return false;
}

function cacheRecords(string $key, array $records, int $seconds = null) {
	if (Cache::has($key)) {
		$records = Cache::get($key);

	} else {
		if ($seconds == null) {
			$seconds = strtotime(date("Y-m-d 02:00", strtotime('tomorrow'))) - strtotime(now());
			// $seconds = 300;
		}

		Cache::put($key, $records, $seconds);
	}

	return $records;
}

function cachePdf(string $fileName, bool $overWrite = false) {
	$publicFileName = sprintf('pdfs/%s.pdf', explode('.', $fileName)[0]);

	if (!Storage::disk('public')->exists($publicFileName) || $overWrite) {
		$data = Storage::get('pdfs/' . $fileName);

		if (!empty($data)) {
			Storage::disk('public')->put($publicFileName, $data);
		}
	}
		
	return Storage::disk('public')->url($publicFileName);
}

function resetShowMarker() {
	if ((empty(session()->get('_previous')['url']) && empty(session()->get('pageShowMarkerPrevious')[0])) || !in_array(explode('?', url()->current())[0], [explode('?', session()->get('pageShowMarkerPrevious'))[0], explode('?', session()->get('_previous')['url'])[0]])) {
		session()->put('pageShowMarker', false);
	}
}

function cacheImage(string $fileName, int $width = 0, int $height = 0, bool $trim = false, string $background = null, bool $webp = true):string {
	$publicFileName = sprintf('images/%s%s%s.%s', 
		explode('.', $fileName)[0], 
		($width > 0 || $height > 0) ? sprintf('-%d-%d', $width, $height) : '',
		$trim ? '-trim' : '',
		$webp ? 'webp' : explode('.', $fileName)[1]
	);

	if (!Storage::disk('public')->exists($publicFileName)) {
		$data = Storage::get($fileName);

		if (!empty($data)) {
			$manager = new ImageManager(['driver' => 'imagick']);
			$image = $manager->make($data);

			if($trim) {
				$image->trim();
			}

			if ($width > 0 || $height > 0) {
				$width = $width > 0 ? $width : null;
				$height = $height > 0 ? $height : null;

				$image->resize($width, $height, function($constraint) {
					$constraint->aspectRatio();
					// $constraint->upsize();
				});

				if (!is_null($background)) {
					$image->resizeCanvas($width, $height, 'center', false, $background);
				}
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

function cacheImages($records, int $width = 0, int $height = 0, bool $trim = false, string $background = null, bool $webp = true) {
	foreach ($records as $i => $record) {
		if (property_exists($record, 'fileName')) {
			if (is_array($record)) {
				if (!empty($record['fileName'])) {
					$record['fileName'] = cacheImage($record['fileName'], $width, $height, $trim, $background, $webp);
				}
			} else {
				if (!empty($record->fileName)) {
					$record->fileName = cacheImage($record->fileName, $width, $height, $trim, $background, $webp);
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
		$mimeType = explode('/', $file->getMimeType())[1];

		if ($mimeType == 'jpeg') {
			$mimeType = 'jpg';
		}

		$fileName = sprintf('images/%s-%s-%s-%s.%s',
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

function storeImageFromString(string $fileName, string $data) {
	Storage::put('images/' . $fileName, $data);

	$asset = Asset::create([
		'name' => $fileName,
		'fileName' => $fileName,
	]);

	return $asset->id;
}

// Klaviyo
function subscribeKlaviyo($userId) {
	try {
		$user = User::find($userId);

		$klaviyo = new KlaviyoAPI(env('KLAVIYO_PRIVATE_KEY'));
		
		$response = $klaviyo->Profiles->createOrUpdateProfile([
			'data' => [
				'type' => 'profile',
				'attributes' => [
					'email' => $user->email,
					// 'external_id', $user->id,
					'first_name' => $user->firstname,
					'last_name' => $user->lastname,
				],
			]
		]);

	} catch (\Throwable $th) {
		dd($th);
		return false;

	} finally {
		$user->klaviyoId = $response['data']['id'];
		$user->save();

		try {
			$response2 = $klaviyo->Profiles->subscribeProfiles([
				'data' => [
					'type' => 'profile-subscription-bulk-create-job',
					'attributes' => [
						'custom_source' => 'Website Sign Up',
						'profiles' => [
							'data' => [
								[
									'type' => 'profile',
									'id' => $user->klaviyoId,
									'attributes' => [
										'email' => $user->email,
										'subscriptions' => [
											'email' => [
												'marketing' => [
													'consent' => 'SUBSCRIBED',
												]
											],
										],
									],
								]
							],
						],
					],
					'relationships' => [
						'list' => [
							'data' => [
								'type' => 'list',
								'id' => env('KLAVIYO_LIST_ID'),
							],
						],
					],
				]
			]);

		} catch (\Throwable $th) {
			// dd($th);
			return false;
		}
	}
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

