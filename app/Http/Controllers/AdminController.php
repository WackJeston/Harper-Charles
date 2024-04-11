<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
  public function __construct()
	{
		$adminLinks = [
			$messages = [
				"title"=>"messages",
				"icon"=>"fa-regular fa-comment",
				"sublink"=>$subLinks = [
					$enquiries = [
						"title"=>"search",
						"link"=>"/admin/enquiries",
						"icon"=>"fa-solid fa-magnifying-glass",
					],
				],
			],
			$orders = [
				"title"=>"orders",
				"icon"=>"fa-solid fa-basket-shopping",
				"sublink"=>$subLinks = [
					$allOrders = [
						"title"=>"search",
						"link"=>"/admin/orders",
						"icon"=>"fa-solid fa-magnifying-glass",
					],
				],
			],
			$people = [
				"title"=>"people",
				"icon"=>"fa-solid fa-users",
				"sublink"=>$subLinks = [
					$customers = [
						"title"=>"customers",
						"link"=>"/admin/customers",
						"icon"=>"fa-solid fa-user",
					],
					$users = [
						"title"=>"users",
						"link"=>"/admin/users",
						"icon"=>"fa-solid fa-user-astronaut",
					],
				],
			],
			$products = [
				"title"=>"products",
				"icon"=>"fa-solid fa-dice-d6",
				"sublink"=>$subLinks = [
					$allProducts = [
						"title"=>"search",
						"link"=>"/admin/products",
						"icon"=>"fa-solid fa-magnifying-glass",
					],
					$categories = [
						"title"=>"categories",
						"link"=>"/admin/categories",
						"icon"=>"fa-solid fa-layer-group",
					],
					$variants = [
						"title"=>"variants",
						"link"=>"/admin/variants",
						"icon"=>"fa-solid fa-shapes",
					],
				],
			],
			$settings = [
				"title"=>"settings",
				"link"=>"/admin/settings",
				"icon"=>"fa-solid fa-gear",
			],
			$website = [
				"title"=>"website",
				"icon"=>"fa-solid fa-globe",
				"sublink"=>$subLinks = [
					$banners = [
						"title"=>"banners",
						"link"=>"/admin/banners",
						"icon"=>"fa-solid fa-sign-hanging",
					],
					$contact = [
						"title"=>"contact",
						"link"=>"/admin/contact",
						"icon"=>"fa-solid fa-address-card",
					],
				],
			],
			$test = [
				"title"=>"test",
				"link"=>"/admin/test",
				"icon"=>"fa-solid fa-flask-vial",
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
			'adminLinks' => $adminLinks,
			'contact' => $contact,
		]);
	}
}
