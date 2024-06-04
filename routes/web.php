<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



// PUBLIC -----------------------------------------------------------------------------------
Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'show']);

Route::controller(App\Http\Controllers\Public\AuthController::class)->group(function () {
  Route::get('/login', 'show');
  Route::get('/loginBasket', 'basketRedirect');
  Route::get('/customerLogin', 'authenticate');
  Route::get('/customerLogout', 'logout');
  Route::get('/sign-up', 'showSignup');
  Route::get('/customerSignup', 'signup');
  Route::get('/verify-email/{id}', 'showVerifyEmail')->middleware('auth')->name('verification.notice');
  Route::get('/verify-email-resend/{id}', 'resendVerifyEmail')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
  Route::get('/email-verified/{id}/{hash}', 'showEmailVerified')->middleware('signed')->name('verification.verify');
});

Route::controller(App\Http\Controllers\Public\SitemapController::class)->group(function () {
	Route::get('/site-map', 'show');
});

Route::controller(App\Http\Controllers\Public\ContactController::class)->group(function () {
  Route::get('/contact', 'show');
  Route::get('/contactCreateEnquiry', 'createEnquiry');
});

Route::controller(App\Http\Controllers\Public\CategoryController::class)->group(function () {
  Route::get('/shop', 'show');
  Route::get('/category/{id}', 'show');
});

Route::controller(App\Http\Controllers\Public\ProductPageController::class)->group(function () {
  Route::get('/product/{id}', 'show');
  Route::post('/product-pageBasketAdd', 'basketAdd');
});

// Auth Middleware
Route::group( ['middleware' => 'auth' ], function()
{
	Route::controller(App\Http\Controllers\Public\BasketController::class)->group(function () {
		Route::get('/basket', 'show');
		Route::get('/basketQuantityUpdate/{id}/{quantity}', 'quantityUpdate');
		Route::get('/basketRemove/{id}', 'basketRemove');
	});
	
	Route::controller(App\Http\Controllers\Public\AccountController::class)->group(function () {
		Route::get('/account', 'show');
		Route::post('/accountUpdate/{id}', 'update');
		Route::get('/account/order/{orderId}', 'orderShow');
		Route::post('/account/orderAddNote/{orderId}', 'orderAddNote');
	});

	Route::controller(App\Http\Controllers\Public\CheckoutController::class)->group(function () {
		Route::get('/checkout/{action}', 'show');
		Route::get('/checkoutContinueAddresses/{deliveryId}', 'continueAddress');
		Route::get('/checkoutAddAddress/{addressData}', 'addAddress');
		Route::get('/checkoutDeleteAddress/{id}', 'deleteAddress');
		Route::get('/checkoutSetBillingAddress/{id}', 'setBillingAddress');
		Route::get('/checkoutSaveOrderNote/{note}', 'saveOrderNote');
		Route::get('/checkoutCompleteOrder/{marketing}', 'completeOrder');
		Route::get('/order-successful/{orderId}', 'orderSuccessful');
	});



	// ADMIN -----------------------------------------------------------------------------------	
  Route::controller(App\Http\Controllers\Admin\AuthController::class)->group(function () {
    Route::get('/admin', 'show');
    Route::get('/adminLogin', 'authenticate');
    Route::get('/adminLogout', 'logout');
  });

  Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'show']);

  Route::controller(App\Http\Controllers\Admin\ContactController::class)->group(function () {
    Route::get('/admin/contact', 'show');
    Route::post('/contactUpdateAddress', 'updateAddress');
    Route::get('/contactUploadLatLng/{lat}/{lng}', 'uploadLatLng');
    Route::post('/contactCreateEmail', 'createEmail');
    Route::get('/contactDeleteEmail/{id}', 'deleteEmail');
    Route::post('/contactCreatePhone', 'createPhone');
    Route::get('/contactDeletePhone/{id}', 'deletePhone');
    Route::post('/contactCreateUrl', 'createUrl');
    Route::get('/contactDeleteUrl/{id}', 'deleteUrl');
  });

  Route::controller(App\Http\Controllers\Admin\SettingsController::class)->group(function () {
    Route::get('/admin/settings', 'show');
    Route::post('/settingsUpdate', 'update');
    Route::get('/settingsClearCache/{key}', 'clearCache');
  });

  Route::controller(App\Http\Controllers\Admin\UsersController::class)->group(function () {
    Route::get('/admin/users', 'show');
    Route::post('/usersCreate', 'create');
  });

  Route::controller(App\Http\Controllers\Admin\UserProfileController::class)->group(function () {
    Route::get('/admin/user-profile/{id}', 'show');
    Route::post('/user-profileUpdate/{id}', 'update');
    Route::get('/user-profileDelete/{id}', 'delete');
  });

  Route::controller(App\Http\Controllers\Admin\CustomersController::class)->group(function () {
    Route::get('/admin/customers', 'show');
    Route::post('/customersCreate', 'create');
  });

  Route::controller(App\Http\Controllers\Admin\CustomerProfileController::class)->group(function () {
    Route::get('/admin/customer-profile/{id}', 'show');
    Route::post('/customer-profileUpdate/{id}', 'update');
    Route::get('/customer-profileDelete/{id}', 'delete');
  });

	Route::controller(App\Http\Controllers\Admin\EnquiriesController::class)->group(function () {
		Route::get('/admin/enquiries', 'show');
    Route::post('/enquiriesSearch', 'search');
	});

	Route::controller(App\Http\Controllers\Admin\EnquiryProfileController::class)->group(function () {
		Route::get('/admin/enquiry-profile/{id}', 'show');
	});

  Route::controller(App\Http\Controllers\Admin\ProductsController::class)->group(function () {
    Route::get('/admin/products', 'show');
    Route::post('/productsCreate', 'create');
    Route::post('/productsSearch', 'search');
  });

  Route::controller(App\Http\Controllers\Admin\ProductProfileController::class)->group(function () {
    Route::get('/admin/product-profile/{id}', 'show');
    Route::get('/product-profileToggleProduct/{product}/{toggle}', 'toggleProduct');
    Route::post('/product-profileUpdate/{id}', 'update');
    Route::get('/product-profileDelete/{id}', 'delete');
    Route::get('/product-profileClearCache/{id}', 'clearCache');
    Route::post('/product-profileAddImage/{id}', 'addImage');
    Route::get('/product-profileDeleteImage/{imageId}', 'deleteImage');
    Route::get('/product-profilePrimaryImage/{imageId}', 'primaryImage');
    Route::post('/product-profileAddCategory/{id}', 'addCategory');
    Route::get('/product-profileRemoveCategory/{id}/{categoryId}', 'removeCategory');
    Route::post('/product-profileAddSpec/{id}', 'addSpec');
    Route::get('/product-profileDeleteSpec/{specId}', 'deleteSpec');
    Route::post('/product-profileAddStock/{id}', 'addStock');
    Route::post('/product-profileAddVariant/{id}', 'addVariant');
    Route::get('/product-profileRemoveVariant/{id}/{variantId}', 'removeVariant');
  });

  Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
    Route::get('/admin/categories', 'show');
    Route::get('/categoryShow/{category}/{toggle}', 'showCategory');
    Route::post('/categoryCreate', 'create');
  });

  Route::controller(App\Http\Controllers\Admin\CategoryProfileController::class)->group(function () {
    Route::get('/admin/category-profile/{id}', 'show');
    Route::post('/category-profileUpdate/{id}', 'update');
    Route::get('/category-profileDelete/{id}', 'delete');
    Route::get('/category-profileClearCache/{id}', 'clearCache');
    Route::post('/category-profileAddImage/{id}', 'addImage');
    Route::get('/category-profileShowCategory/{category}/{toggle}', 'showCategory');
    Route::get('/category-profileDeleteImage/{imageId}', 'deleteImage');
    Route::get('/category-profilePrimaryImage/{imageId}', 'primaryImage');
    Route::post('/category-profileAddProduct/{id}', 'addProduct');
    Route::post('/category-profileCreateProduct/{id}', 'createProduct');
    Route::get('/category-profileRemoveProduct/{id}/{productId}', 'removeProduct');
  });


  Route::controller(App\Http\Controllers\Admin\VariantController::class)->group(function () {
    Route::get('/admin/variants', 'show');
    Route::get('/variantShow/{variant}/{toggle}', 'showVariant');
    Route::post('/variantCreate', 'create');
  });

  Route::controller(App\Http\Controllers\Admin\VariantProfileController::class)->group(function () {
    Route::get('/admin/variant-profile/{id}', 'show');
    Route::post('/variant-profileUpdate/{id}', 'update');
    Route::get('/variant-profileDelete/{id}', 'delete');
    Route::get('/variant-profileShowVariant/{variant}/{toggle}', 'showVariant');
    Route::post('/variant-profileCreateOption/{id}', 'createOption');
    Route::get('/variant-profileDeleteOption/{id}/{optionId}', 'deleteOption');
    Route::get('/variant-profileShowOption/{id}/{optionId}/{toggle}', 'showOption');
  });

	Route::controller(App\Http\Controllers\Admin\OrdersController::class)->group(function () {
    Route::get('/admin/orders', 'show');
    Route::post('/ordersSearch', 'search');
  });

	Route::controller(App\Http\Controllers\Admin\OrderProfileController::class)->group(function () {
    Route::get('/admin/order-profile/{id}', 'show');
    Route::post('/order-profileAddNote/{id}', 'addNote');
    Route::post('/order-profileUpdateDelivery/{id}', 'updateDelivery');
    Route::get('/order-profileProceed/{id}', 'proceed');
  });

	Route::controller(App\Http\Controllers\Admin\BannersController::class)->group(function () {
    Route::get('/admin/banners', 'show');
  });

	Route::controller(App\Http\Controllers\Admin\BannerProfileController::class)->group(function () {
    Route::get('/admin/banner-profile/{id}', 'show');
    Route::get('/banner-profileToggleBanner/{id}/{toggle}', 'toggleBanner');
    Route::post('/banner-profileAddSlide/{id}', 'addSlide');
    Route::get('/banner-profileDeleteSlide/{id}', 'deleteSlide');
  });
});



// API -----------------------------------------------------------------------------------
Route::get('/header-toggleNotification/{id}/{notificationUserId}/{type}', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'toggleNotification']);

Route::get('/dataTable-toggleButton/{table}/{column}/{primaryColumn}/{primaryValue}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'toggleButton']);
Route::get('/dataTable-setPrimary/{table}/{column}/{primaryColumn}/{primaryValue}/{parent}/{parentId}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'setPrimary']);
Route::get('/dataTable-selectDropdown/{table}/{column}/{primaryColumn}/{primaryValue}/{value}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'selectDropdown']);
Route::get('/dataTable-resetTableSequence/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'resetTableSequence']);
Route::get('/dataTable-moveSequence/{id}/{direction}/{tabelName}/{sequenceColumn}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'moveSequence']);

Route::get('/dataTable-setOrderColumn/{name}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'setOrderColumn']);
Route::get('/dataTable-setOrderDirection/{direction}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'setOrderDirection']);

Route::get('/dataTable-changeLimit/{limit}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'changeLimit']);
Route::get('/dataTable-changePage/{offset}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'changePage']);

Route::get('/dataTable-resetTableSequence/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'resetTableSequence']);



// TEMPLATES -----------------------------------------------------------------------------------
Route::get('/template/invoice', fn () => view('templates/invoice'));



// FUNCTIONS -----------------------------------------------------------------------------------
Route::get("sitemap-xml" , function () {
	return Illuminate\Support\Facades\Redirect::to('https://ipswich-fireworks.s3.eu-west-2.amazonaws.com/public-assets/sitemap.xml');
});

Route::get("/functions-setShowMarker/{section}" , function ($section = false) {
	if ($section == false || $section == session()->get('pageShowMarker', $section)) {
		session()->put('pageShowMarker', false);
		session()->put('pageShowMarkerPrevious', false);
	} else {
		session()->put('pageShowMarker', $section);
		session()->put('pageShowMarkerPrevious', session()->get('_previous')['url']);
	}
});
