<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductCategories;

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
      Validator::extend('unique_custom', function ($attribute, $value, $parameters)
      {
        list($table, $field, $field2, $field2Value) = $parameters;
        return DB::table($table)->where($field, $value)->where($field2, $field2Value)->count() == 0;
      });

      // View::share('siteTitle', 'Web West');
      View::share('siteTitle', 'Harper Charles');

      if (str_contains(url()->current(), '/admin/')) {
        $adminLinks = [
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
        ];

        View::share([
          'adminLinks' => $adminLinks,
        ]);
      }

      else {
        $publicLinks = [
          $products = [
            "title"=>"products",
            "link"=>"/products/0",
            "icon"=>"fa-solid fa-couch",
            "sublink"=>$subLinks = [],
          ],
          $contact = [
            "title"=>"contact",
            "link"=>"/contact",
            "icon"=>"fa-solid fa-address-card",
          ],
          $cart = [
            "title"=>"cart",
            "link"=>"/cart",
            "icon"=>"fa-solid fa-cart-shopping",
            "headericon"=>"fa-solid fa-cart-shopping",
          ],
          // $test = [
          //   "title"=>"Test Links",
          //   "icon"=>"fa-solid fa-flask-vial",
          //   "sublink"=>$subLinks = [
          //     $test1 = [
          //       "title"=>"First Test",
          //       "link"=>"/test",
          //       "icon"=>"fa-solid fa-microscope",
          //     ],
          //     $test2 = [
          //       "title"=>"Second Test",
          //       "link"=>"/test",
          //       "icon"=>"fa-solid fa-vial-virus",
          //     ],
          //   ],
          // ],
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

        $categories = DB::select('SELECT
          c.id,
          c.title
          FROM product_categories AS c
          WHERE c.show=1
        ');

        foreach ($categories as $i => $category) {
          $publicLinks[0]['sublink'][$i] = [
            "title"=>$category->title,
            "link"=>"/products/" . $category->id,
            "icon"=>"",
          ];
        }

        View::share([
          'publicLinks' => $publicLinks,
          'userLinks' => $userLinks,
        ]);
      }
    }
}
