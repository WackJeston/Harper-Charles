<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductCategories;

// use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
			// Cashier::calculateTaxes();

      Validator::extend('unique_custom', function ($attribute, $value, $parameters)
      {
        list($table, $field, $field2, $field2Value) = $parameters;
        return DB::table($table)->where($field, $value)->where($field2, $field2Value)->count() == 0;
      });

      if (str_contains(url()->current(), '/admin/')) {
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
              $contact = [
                "title"=>"contact",
                "link"=>"/admin/contact",
                "icon"=>"fa-solid fa-address-card",
              ],
              $landingZones = [
                "title"=>"landing zones",
                "link"=>"/admin/landing-zones",
                "icon"=>"fa-solid fa-plane-arrival",
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

      else {
        $publicLinks = [
          $cart = [
            "title"=>"cart",
            "link"=>"/cart",
            "icon"=>"fa-solid fa-cart-shopping",
          ],
          $products = [
            "title"=>"shop",
            "link"=>"/products/0",
            "icon"=>"fa-solid fa-tags",
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

        $categories = DB::select('SELECT
          c.id,
          c.title
          FROM product_categories AS c
          WHERE c.show=1
        ');

        foreach ($categories as $i => $category) {
          $publicLinks[1]['sublink'][$i] = [
            "title"=>$category->title,
            "link"=>"/products/" . $category->id,
            "icon"=>"",
          ];
        }

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
}
