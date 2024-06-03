<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\View;

class PublicController extends Controller
{
  public function __construct()
	{
		$publicLinks = [
			$shop = [
				"title"=>"shop",
				"link"=>"/shop",
				"icon"=>"fa-solid fa-tags",
			],
			$basket = [
				"title"=>"basket",
				"link"=>"/basket",
				"icon"=>"fa-solid fa-basket-shopping",
			],
			$contact = [
				"title"=>"contact",
				"link"=>"/contact",
				"icon"=>"fa-regular fa-address-card",
			],
		];

		$userLinks = [
			$account = [
				"title"=>"account",
				"link"=>"/account",
				"icon"=>"fa-solid fa-user-gear",
			],
			$logout =[
				"title"=>"logout",
				"link"=>"/customerLogout",
				"icon"=>"fa-solid fa-arrow-right-from-bracket",
			],
		];

		$socials = [
			$instagram = [
				"title"=>"instagram",
				"link"=>"https://www.instagram.com/harpercharlescompany/",
				"icon"=>"fa-brands fa-instagram",
			],
			$facebook = [
				"title"=>"facebook",
				"link"=>"https://www.facebook.com/p/Harper-Charles-Bespoke-Interiors-100033144487745/",
				"icon"=>"fa-brands fa-facebook",
			],
			$youtube = [
				"title"=>"youtube",
				"link"=>"https://www.youtube.com/channel/UCr4e-CcIQWgoMBT_XpPC0HQ/",
				"icon"=>"fa-brands fa-youtube",
			],
		];

		$contactResult = DB::select('SELECT type, value FROM contact ORDER BY type ASC');

		$contact = [
			'email' => [],
			'phone' => [],
			'line2' => '',
			'line3' => '',
		];

		foreach ($contactResult as $i => $row) {
			if ($row->type == 'email') {
				$contact['email'][] = $row->value;
			} elseif ($row->type == 'phone') {
				$contact['phone'][] = $row->value;
			}	else {
				$contact[$row->type] = $row->value;
			}
		}

		View::share([
			'publicLinks' => $publicLinks,
			'userLinks' => $userLinks,
			'socials' => $socials,
			'contact' => $contact,
		]);
	}
}
