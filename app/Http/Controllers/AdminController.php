<?php
namespace App\Http\Controllers;

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
						"title"=>"enquiries",
						"link"=>"/admin/enquiries",
						"icon"=>"fa-solid fa-envelope",
					],
				],
			],
			$orders = [
				"title"=>"orders",
				"icon"=>"fa-solid fa-basket-shopping",
				"sublink"=>$subLinks = [
					$allOrders = [
						"title"=>"all orders",
						"link"=>"/admin/orders",
						"icon"=>"fa-solid fa-box-archive",
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
						"title"=>"all products",
						"link"=>"/admin/products",
						"icon"=>"fa-solid fa-cubes",
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

		View::share([
			'adminLinks' => $adminLinks,
		]);
	}
}
