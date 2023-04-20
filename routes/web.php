<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\AuthController;

// PUBLIC
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductPageController;

// ADMIN
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminUserProfileController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminCustomerProfileController;
use App\Http\Controllers\AdminProductsController;
use App\Http\Controllers\AdminProductProfileController;
use App\Http\Controllers\AdminLandingZonesController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCategoryProfileController;
use App\Http\Controllers\AdminVariantController;
use App\Http\Controllers\AdminVariantProfileController;


// PUBLIC
Route::controller(AuthController::class)->group(function () {
  Route::get('/login', 'veiwLogin');
  Route::get('/loginCart', 'veiwLoginCart');
  Route::get('/customerLogin', 'authenticateCustomer');
  Route::get('/customerLogout', 'logoutCustomer');
  Route::get('/sign-up', 'veiwSignup');
  Route::get('/customerSignup', 'signupCustomer');
  Route::get('/verify-email/{id}', 'viewVerifyEmailCustomer')->middleware('auth')->name('verification.notice');
  Route::get('/resend-verify-email/{id}', 'resendVerifyEmailCustomer')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
  Route::get('/email-verified/{id}/{hash}', 'emailVerifiedCustomer')->middleware('signed')->name('verification.verify');
});

Route::get('/', [HomeController::class, 'show']);

Route::get('/contact', [ContactController::class, 'show']);

Route::controller(AccountController::class)->group(function () {
  Route::get('/account', 'show');
  Route::post('/accountUpdate/{id}', 'update');
});

Route::controller(CartController::class)->group(function () {
  Route::get('/cart', 'show');
  Route::post('/apiQuantityUpdate/{item}/{quantity}', 'quantityUpdate');
  Route::get('/cartRemove/{item}', 'cartRemove');
  Route::get('/continueToCheckout', 'continueToCheckout');
});

Route::controller(CheckoutController::class)->group(function () {
  Route::get('/checkout/{action}', 'show');
  Route::get('/checkoutContinueAddresses/{delivery}/{billing}', 'continueAddress');
  Route::post('/checkoutAddAddress/{type}/{addressData}', 'addAddress');
  Route::post('/checkoutDeleteAddress/{id}', 'deleteAddress');
  Route::post('/checkoutDefaultAddress/{type}/{id}', 'defaultAddress');
  Route::post('/checkoutCreatePaymentIntent', 'createPaymentIntent');
});

Route::get('/products/{id}', [ProductsController::class, 'show']);

Route::controller(ProductPageController::class)->group(function () {
  Route::get('/product-page/{id}', 'show');
  Route::post('/product-pageCartAdd/{id}/{variantCount}/{selectedVariants}', 'cartAdd');
});


// ADMIN
Route::group( ['middleware' => 'auth' ], function()
{
  Route::view('/admin', 'admin/auth/login');
  Route::controller(AuthController::class)->group(function () {
    Route::get('/adminLogin', 'authenticateAdmin');
    Route::get('/adminLogout', 'logoutAdmin');
  });

  Route::get('/admin/dashboard', [AdminDashboardController::class, 'show']);

  Route::controller(AdminContactController::class)->group(function () {
    Route::get('/admin/contact', 'show');
    Route::post('/contactUpdateAddress/{lat}/{lng}', 'updateAddress');
    Route::post('/contactCreateEmail', 'createEmail');
    Route::get('/contactDeleteEmail/{id}', 'deleteEmail');
    Route::post('/contactCreatePhone', 'createPhone');
    Route::get('/contactDeletePhone/{id}', 'deletePhone');
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

  Route::controller(AdminProductsController::class)->group(function () {
    Route::get('/admin/products', 'show');
    Route::post('/productsCreate', 'create');
  });

  Route::controller(AdminProductProfileController::class)->group(function () {
    Route::get('/admin/product-profile/{id}', 'show');
    Route::post('/product-profileUpdate/{id}', 'update');
    Route::get('/product-profileDelete/{id}', 'delete');
    Route::post('/product-profileStoreImage/{id}', 'storeImage');
    Route::get('/product-profileDeleteImage/{imageId}', 'deleteImage');
    Route::get('/product-profilePrimaryImage/{imageId}', 'primaryImage');
    Route::post('/product-profileAddCategory/{id}', 'addCategory');
    Route::get('/product-profileRemoveCategory/{id}/{categoryId}', 'removeCategory');
    Route::post('/product-profileAddVariant/{id}', 'addVariant');
    Route::get('/product-profileRemoveVariant/{id}/{variantId}', 'removeVariant');
  });

  Route::controller(AdminLandingZonesController::class)->group(function () {
    Route::get('/admin/landing-zones', 'show');
    Route::get('/landing-zonesShowZone/{zone}/{toggle}', 'showZone');
    Route::post('/landing-zonesStoreSlide/{id}', 'storeSlide');
    Route::get('landing-zonesDeleteSlide/{slideId}', 'deleteSlide');
    Route::get('landing-zonesPrimarySlide/{slideId}', 'primarySlide');
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
    Route::post('/category-profileStoreImage/{id}', 'storeImage');
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
});
