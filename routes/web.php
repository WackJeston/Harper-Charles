<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\AuthController;

//DataTable
use App\Http\Controllers\DataTableController;

// SYSTEM
use App\Http\Controllers\TestController;

// PUBLIC
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SitemapController;

// ADMIN
use App\Http\Controllers\AdminHeaderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminUserProfileController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminCustomerProfileController;
use App\Http\Controllers\AdminEnquiriesController;
use App\Http\Controllers\AdminEnquiryProfileController;
use App\Http\Controllers\AdminProductsController;
use App\Http\Controllers\AdminProductProfileController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCategoryProfileController;
use App\Http\Controllers\AdminVariantController;
use App\Http\Controllers\AdminVariantProfileController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminOrderProfileController;
use App\Http\Controllers\AdminBannersController;
use App\Http\Controllers\AdminBannerProfileController;


// DataTable -----------------------------------------------------------------------------------
Route::get('/dataTable-toggleButton/{table}/{column}/{primaryColumn}/{primaryValue}', [DataTableController::class, 'toggleButton']);
Route::get('/dataTable-setPrimary/{table}/{column}/{primaryColumn}/{primaryValue}/{parent}/{parentId}', [DataTableController::class, 'setPrimary']);
Route::get('/dataTable-selectDropdown/{table}/{column}/{primaryColumn}/{primaryValue}/{value}', [DataTableController::class, 'selectDropdown']);
Route::get('/dataTable-resetTableSequence/{sessionVariable}', [DataTableController::class, 'resetTableSequence']);
Route::get('/dataTable-moveSequence/{id}/{direction}/{tabelName}/{sequenceColumn}', [DataTableController::class, 'moveSequence']);

Route::get('/dataTable-setOrderColumn/{name}/{sessionVariable}', [DataTableController::class, 'setOrderColumn']);
Route::get('/dataTable-setOrderDirection/{direction}/{sessionVariable}', [DataTableController::class, 'setOrderDirection']);

Route::get('/dataTable-changeLimit/{limit}/{sessionVariable}', [DataTableController::class, 'changeLimit']);
Route::get('/dataTable-changePage/{offset}/{sessionVariable}', [DataTableController::class, 'changePage']);

Route::get('/dataTable-resetTableSequence/{sessionVariable}', [DataTableController::class, 'resetTableSequence']);


// SYSTEM -----------------------------------------------------------------------------------
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

// PUBLIC -----------------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'show']);

Route::controller(AuthController::class)->group(function () {
  Route::get('/login', 'veiwLogin');
  Route::get('/loginBasket', 'veiwLoginBasket');
  Route::get('/customerLogin', 'authenticateCustomer');
  Route::get('/customerLogout', 'logoutCustomer');
  Route::get('/sign-up', 'veiwSignup');
  Route::get('/customerSignup', 'signupCustomer');
  Route::get('/verify-email/{id}', 'viewVerifyEmailCustomer')->middleware('auth')->name('verification.notice');
  Route::get('/verify-email-resend/{id}', 'resendVerifyEmailCustomer')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
  Route::get('/email-verified/{id}/{hash}', 'emailVerifiedCustomer')->middleware('signed')->name('verification.verify');
});

Route::controller(SitemapController::class)->group(function () {
	Route::get('/site-map', 'show');
});

Route::controller(ContactController::class)->group(function () {
  Route::get('/contact', 'show');
  Route::get('/contactCreateEnquiry', 'createEnquiry');
});

Route::controller(CategoryController::class)->group(function () {
  Route::get('/shop', 'show');
  Route::get('/category/{id}', 'show');
});

Route::controller(ProductPageController::class)->group(function () {
  Route::get('/product/{id}', 'show');
  Route::post('/product-pageBasketAdd', 'basketAdd');
});

// Auth Middleware
Route::group( ['middleware' => 'auth' ], function()
{
	Route::controller(BasketController::class)->group(function () {
		Route::get('/basket', 'show');
		Route::get('/basketQuantityUpdate/{id}/{quantity}', 'quantityUpdate');
		Route::get('/basketRemove/{id}', 'basketRemove');
	});
	
	Route::controller(AccountController::class)->group(function () {
		Route::get('/account', 'show');
		Route::post('/accountUpdate/{id}', 'update');
		Route::get('/account/order/{orderId}', 'orderShow');
		Route::post('/account/orderAddNote/{orderId}', 'orderAddNote');
	});

	Route::controller(CheckoutController::class)->group(function () {
		Route::get('/checkout/{action}', 'show');
		Route::get('/checkoutContinueAddresses/{deliveryId}', 'continueAddress');
		Route::get('/checkoutAddAddress/{addressData}', 'addAddress');
		Route::get('/checkoutDeleteAddress/{id}', 'deleteAddress');
		Route::get('/checkoutSetBillingAddress/{id}', 'setBillingAddress');
		Route::get('/checkoutCompleteOrder', 'completeOrder');
		Route::get('/order-successful/{orderId}', 'orderSuccessful');
	});

	// ADMIN -----------------------------------------------------------------------------------
	Route::get('/header-toggleNotification/{id}/{notificationUserId}/{type}', [AdminHeaderController::class, 'toggleNotification']);
  
	Route::view('/admin', 'admin/auth/login');
	
  Route::controller(AuthController::class)->group(function () {
    Route::get('/adminLogin', 'authenticateAdmin');
    Route::get('/adminLogout', 'logoutAdmin');
  });

	Route::get('/admin/test', [AdminTestController::class, 'show']);

  Route::get('/admin/dashboard', [AdminDashboardController::class, 'show']);

  Route::controller(AdminContactController::class)->group(function () {
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

  Route::controller(AdminSettingsController::class)->group(function () {
    Route::get('/admin/settings', 'show');
    Route::post('/settingsUpdate', 'update');
    Route::get('/settingsClearCache/{key}', 'clearCache');
  });

  Route::controller(AdminUsersController::class)->group(function () {
    Route::get('/admin/users', 'show');
    Route::post('/usersCreate', 'create');
  });

  Route::controller(AdminUserProfileController::class)->group(function () {
    Route::get('/admin/user-profile/{id}', 'show');
    Route::post('/user-profileUpdate/{id}', 'update');
    Route::get('/user-profileDelete/{id}', 'delete');
  });

  Route::controller(AdminCustomersController::class)->group(function () {
    Route::get('/admin/customers', 'show');
    Route::post('/customersCreate', 'create');
  });

  Route::controller(AdminCustomerProfileController::class)->group(function () {
    Route::get('/admin/customer-profile/{id}', 'show');
    Route::post('/customer-profileUpdate/{id}', 'update');
    Route::get('/customer-profileDelete/{id}', 'delete');
  });

	Route::controller(AdminEnquiriesController::class)->group(function () {
		Route::get('/admin/enquiries', 'show');
    Route::post('/enquiriesSearch', 'search');
	});

	Route::controller(AdminEnquiryProfileController::class)->group(function () {
		Route::get('/admin/enquiry-profile/{id}', 'show');
	});

  Route::controller(AdminProductsController::class)->group(function () {
    Route::get('/admin/products', 'show');
    Route::post('/productsCreate', 'create');
    Route::post('/productsSearch', 'search');
  });

  Route::controller(AdminProductProfileController::class)->group(function () {
    Route::get('/admin/product-profile/{id}', 'show');
    Route::get('/product-profileToggleProduct/{product}/{toggle}', 'toggleProduct');
    Route::post('/product-profileUpdate/{id}', 'update');
    Route::get('/product-profileDelete/{id}', 'delete');
    Route::get('/product-profileClearCache/{id}', 'clearCache');
    Route::post('/product-profileAddImage/{id}', 'addImage');
    Route::get('/product-profileDeleteImage/{imageId}', 'deleteImage');
    Route::get('/product-profilePrimaryImage/{imageId}', 'primaryImage');
    Route::post('/product-profileAddSpec/{id}', 'addSpec');
    Route::get('/product-profileDeleteSpec/{specId}', 'deleteSpec');
    Route::post('/product-profileAddCategory/{id}', 'addCategory');
    Route::get('/product-profileRemoveCategory/{id}/{categoryId}', 'removeCategory');
    Route::post('/product-profileAddVariant/{id}', 'addVariant');
    Route::get('/product-profileRemoveVariant/{id}/{variantId}', 'removeVariant');
  });

  Route::controller(AdminCategoryController::class)->group(function () {
    Route::get('/admin/categories', 'show');
    Route::get('/categoryShow/{category}/{toggle}', 'showCategory');
    Route::post('/categoryCreate', 'create');
  });

  Route::controller(AdminCategoryProfileController::class)->group(function () {
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


  Route::controller(AdminVariantController::class)->group(function () {
    Route::get('/admin/variants', 'show');
    Route::get('/variantShow/{variant}/{toggle}', 'showVariant');
    Route::post('/variantCreate', 'create');
  });

  Route::controller(AdminVariantProfileController::class)->group(function () {
    Route::get('/admin/variant-profile/{id}', 'show');
    Route::post('/variant-profileUpdate/{id}', 'update');
    Route::get('/variant-profileDelete/{id}', 'delete');
    Route::get('/variant-profileShowVariant/{variant}/{toggle}', 'showVariant');
    Route::post('/variant-profileCreateOption/{id}', 'createOption');
    Route::get('/variant-profileDeleteOption/{id}/{optionId}', 'deleteOption');
    Route::get('/variant-profileShowOption/{id}/{optionId}/{toggle}', 'showOption');
  });

	Route::controller(AdminOrdersController::class)->group(function () {
    Route::get('/admin/orders', 'show');
    Route::post('/ordersSearch', 'search');
  });

	Route::controller(AdminOrderProfileController::class)->group(function () {
    Route::get('/admin/order-profile/{id}', 'show');
    Route::post('/order-profileAddNote/{id}', 'addNote');
  });

	Route::controller(AdminBannersController::class)->group(function () {
    Route::get('/admin/banners', 'show');
  });

	Route::controller(AdminBannerProfileController::class)->group(function () {
    Route::get('/admin/banner-profile/{id}', 'show');
    Route::get('/banner-profileToggleBanner/{id}/{toggle}', 'toggleBanner');
    Route::post('/banner-profileAddSlide/{id}', 'addSlide');
    Route::get('/banner-profileDeleteSlide/{id}', 'deleteSlide');
  });
});


// TEMPLATES -----------------------------------------------------------------------------------
Route::get('/template/invoice', fn () => view('templates/invoice'));
