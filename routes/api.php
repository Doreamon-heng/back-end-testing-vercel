<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    // Public auth routes
    Route::post('/register', [\App\Http\Controllers\api\AuthController::class, 'register'])->name('register');
    Route::post('/login', [\App\Http\Controllers\api\AuthController::class, 'login'])->name('login');
    Route::post('/forgot-password', [\App\Http\Controllers\api\AuthController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [\App\Http\Controllers\api\AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/recovery-account', [\App\Http\Controllers\api\AuthController::class, 'recoveryAccount'])->name('recovery.account');
    Route::post('/recovery-account-by-phone', [\App\Http\Controllers\api\AuthController::class, 'recoveryAccountByPhone'])->name('recovery.account.phone');

    // Bakong webhook (public)
    Route::post('/bakong/webhook', [\App\Http\Controllers\api\PaymentController::class, 'webhook'])->name('bakong.webhook');

    // Any authenticated user can log out
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', [\App\Http\Controllers\api\AuthController::class, 'logout'])->name('logout');
    });

    // Admin-only routes (protected)
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        // Roles management
        Route::get('/roles', [\App\Http\Controllers\api\RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{id}', [\App\Http\Controllers\api\RoleController::class, 'show'])->name('roles.show');
        Route::post('/roles', [\App\Http\Controllers\api\RoleController::class, 'store'])->name('roles.store');
        Route::put('/roles/{id}', [\App\Http\Controllers\api\RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [\App\Http\Controllers\api\RoleController::class, 'destroy'])->name('roles.destroy');

        // User roles management
        Route::get('/roles-user', [\App\Http\Controllers\api\Role_userController::class, 'index'])->name('roles-user.index');
        Route::get('/roles-user/{id}', [\App\Http\Controllers\api\Role_userController::class, 'show'])->name('roles-user.show');
        Route::post('/roles-user', [\App\Http\Controllers\api\Role_userController::class, 'store'])->name('roles-user.store');
        Route::put('/roles-user/{id}', [\App\Http\Controllers\api\Role_userController::class, 'update'])->name('roles-user.update');
        Route::delete('/roles-user/{id}', [\App\Http\Controllers\api\Role_userController::class, 'destroy'])->name('roles-user.destroy');

        // User management
        Route::get('/users', [\App\Http\Controllers\api\UserController::class, 'getAllUsers'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\api\UserController::class, 'createUser'])->name('users.store');
        Route::get('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'getUserById'])->name('users.show');
        Route::put('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'deleteUser'])->name('users.destroy');

        // Customer management
        Route::get('/customers', [\App\Http\Controllers\api\CustomerController::class, 'getAllCustomers'])->name('customers.index');
        Route::post('/customers', [\App\Http\Controllers\api\CustomerController::class, 'createCustomer'])->name('customers.store');
        Route::get('/customers/{id}', [\App\Http\Controllers\api\CustomerController::class, 'getCustomerDetails'])->name('customers.show');
        Route::put('/customers/{id}', [\App\Http\Controllers\api\CustomerController::class, 'updateCustomer'])->name('customers.update');
        Route::delete('/customers/{id}', [\App\Http\Controllers\api\CustomerController::class, 'deleteCustomer'])->name('customers.destroy');

        // Category management
        Route::get('/categories', [\App\Http\Controllers\api\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{id}', [\App\Http\Controllers\api\CategoryController::class, 'show'])->name('categories.show');
        Route::post('/categories', [\App\Http\Controllers\api\CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [\App\Http\Controllers\api\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [\App\Http\Controllers\api\CategoryController::class, 'destroy'])->name('categories.destroy');

        // Product management
        Route::get('/products', [\App\Http\Controllers\api\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [\App\Http\Controllers\api\ProductController::class, 'show'])->name('products.show');
        Route::post('/products', [\App\Http\Controllers\api\ProductController::class, 'create'])->name('products.create');
        Route::put('/products/{id}', [\App\Http\Controllers\api\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [\App\Http\Controllers\api\ProductController::class, 'destroy'])->name('products.destroy');

        // Product image management
        Route::get('/products_image', [\App\Http\Controllers\api\Product_imageController::class, 'index'])->name('products_image.index');
        Route::get('/products_image/{id}', [\App\Http\Controllers\api\Product_imageController::class, 'detailsProductImage'])->name('products_image.show');
        Route::post('/products_image', [\App\Http\Controllers\api\Product_imageController::class, 'createProductImage'])->name('products_image.store');
        Route::put('/products_image/{id}', [\App\Http\Controllers\api\Product_imageController::class, 'updateProductImage'])->name('products_image.update');
        Route::delete('/products_image/{id}', [\App\Http\Controllers\api\Product_imageController::class, 'deleteProductImage'])->name('products_image.destroy');

        // // Bank management
        // Route::get('/banks', [\App\Http\Controllers\api\BankController::class, 'getBank'])->name('banks.index');
        // Route::get('/banks/{id}', [\App\Http\Controllers\api\BankController::class, 'detailsBank'])->name('banks.show');
        // Route::post('/banks', [\App\Http\Controllers\api\BankController::class, 'createBank'])->name('banks.store');
        // Route::put('/banks/{id}', [\App\Http\Controllers\api\BankController::class, 'updateBank'])->name('banks.update');
        // Route::delete('/banks/{id}', [\App\Http\Controllers\api\BankController::class, 'deleteBank'])->name('banks.destroy');

        // Warranty management
        Route::get('/warranties', [\App\Http\Controllers\api\WarrantyController::class, 'index'])->name('warranties.index');
        Route::get('/warranties/{id}', [\App\Http\Controllers\api\WarrantyController::class, 'show'])->name('warranties.show');
        Route::post('/warranties', [\App\Http\Controllers\api\WarrantyController::class, 'store'])->name('warranties.store');
        Route::put('/warranties/{id}', [\App\Http\Controllers\api\WarrantyController::class, 'update'])->name('warranties.update');
        Route::delete('/warranties/{id}', [\App\Http\Controllers\api\WarrantyController::class, 'destroy'])->name('warranties.destroy');

        // OTP management
        Route::get('/otp', [\App\Http\Controllers\api\OtpsController::class, 'index'])->name('otp.index');
        Route::get('/otp/{id}', [\App\Http\Controllers\api\OtpsController::class, 'show'])->name('otp.show');
        Route::post('/otp', [\App\Http\Controllers\api\OtpsController::class, 'store'])->name('otp.store');
        Route::put('/otp/{id}', [\App\Http\Controllers\api\OtpsController::class, 'update'])->name('otp.update');
        Route::delete('/otp/{id}', [\App\Http\Controllers\api\OtpsController::class, 'destroy'])->name('otp.destroy');

        // Slide show management
        Route::get('/slide_show', [\App\Http\Controllers\api\Slide_showController::class, 'index'])->name('slide_show.index');
        Route::get('/slide_show/{id}', [\App\Http\Controllers\api\Slide_showController::class, 'show'])->name('slide_show.show');
        Route::post('/slide_show', [\App\Http\Controllers\api\Slide_showController::class, 'store'])->name('slide_show.store');
        Route::put('/slide_show/{id}', [\App\Http\Controllers\api\Slide_showController::class, 'update'])->name('slide_show.update');
        Route::delete('/slide_show/{id}', [\App\Http\Controllers\api\Slide_showController::class, 'destroy'])->name('slide_show.destroy');

        // Slide show image management
        Route::get('/slide_show_images', [\App\Http\Controllers\api\SlideShowImageController::class, 'index'])->name('slide_show_image.index');
        Route::get('/slide_show_images/{id}', [\App\Http\Controllers\api\SlideShowImageController::class, 'show'])->name('slide_show_image.show');
        Route::post('/slide_show_images', [\App\Http\Controllers\api\SlideShowImageController::class, 'store'])->name('slide_show_image.store');
        Route::put('/slide_show_images/{id}', [\App\Http\Controllers\api\SlideShowImageController::class, 'update'])->name('slide_show_image.update');
        Route::delete('/slide_show_images/{id}', [\App\Http\Controllers\api\SlideShowImageController::class, 'destroy'])->name('slide_show_image.destroy');

        // Stock management
        Route::get('/stocks', [\App\Http\Controllers\api\StockController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/{id}', [\App\Http\Controllers\api\StockController::class, 'show'])->name('stocks.show');
        Route::post('/stocks', [\App\Http\Controllers\api\StockController::class, 'store'])->name('stocks.store');
        Route::put('/stocks/{id}', [\App\Http\Controllers\api\StockController::class, 'update'])->name('stocks.update');
        Route::delete('/stocks/{id}', [\App\Http\Controllers\api\StockController::class, 'destroy'])->name('stocks.destroy');

        // Company info management
        Route::get('/company_info', [\App\Http\Controllers\api\Company_infoController::class, 'index'])->name('company_info.index');
        Route::get('/company_info/{id}', [\App\Http\Controllers\api\Company_infoController::class, 'show'])->name('company_info.show');
        Route::post('/company_info', [\App\Http\Controllers\api\Company_infoController::class, 'store'])->name('company_info.store');
        Route::put('/company_info/{id}', [\App\Http\Controllers\api\Company_infoController::class, 'update'])->name('company_info.update');
        Route::delete('/company_info/{id}', [\App\Http\Controllers\api\Company_infoController::class, 'destroy'])->name('company_info.destroy');

        // Category image management
        Route::get('/categories_image/', [\App\Http\Controllers\api\Category_imageController::class, 'getAllCateImage'])->name('categories_image.index');
        Route::get('/categories_image/{id}', [\App\Http\Controllers\api\Category_imageController::class, 'detailsCateImage'])->name('categories_image.show');
        Route::post('/categories_image', [\App\Http\Controllers\api\Category_imageController::class, 'createCateImage'])->name('categories_image.store');
        Route::put('/categories_image/{id}', [\App\Http\Controllers\api\Category_imageController::class, 'updateCateImage'])->name('categories_image.update');
        Route::put('/categories_image', [\App\Http\Controllers\api\Category_imageController::class, 'updateCateImage'])->name('categories_image.update_no_id');
        Route::delete('/categories_image/{id}', [\App\Http\Controllers\api\Category_imageController::class, 'destroyCateImage'])->name('categories_image.delete');
    });

    //Route for editor roles
    // Route::middleware(['auth:sanctum', 'editor'])->group(function () {
    //     // Define routes for editor role here
    //     Route::get('/editor-dashboard', [\App\Http\Controllers\api\EditorController::class, 'dashboard'])->name('editor.dashboard');
    //     // Add more editor-specific routes as needed
    // });

    // route for user (client) role
    Route::middleware(['auth:sanctum', 'user'])->group(function () {

        // Profile management
        Route::get('/profile', [\App\Http\Controllers\api\UserController::class, 'getProfile'])->name('profile.show');
        Route::put('/profile', [\App\Http\Controllers\api\UserController::class, 'updateProfile'])->name('profile.update');
        Route::delete('/profile', [\App\Http\Controllers\api\UserController::class, 'deleteProfile'])->name('profile.destroy');

        // Products (read-only for clients)
        Route::get('/products', [\App\Http\Controllers\api\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [\App\Http\Controllers\api\ProductController::class, 'show'])->name('products.show');

        // Categories (read-only)
        Route::get('/categories', [\App\Http\Controllers\api\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{id}', [\App\Http\Controllers\api\CategoryController::class, 'show'])->name('categories.show');

        // Category images (read-only)
        Route::get('/categories_images', [\App\Http\Controllers\api\Category_imageController::class, 'getAllCateImage'])->name('categories_images.index');
        Route::get('/categories_images/{id}', [\App\Http\Controllers\api\Category_imageController::class, 'detailsCateImage'])->name('categories_images.show');

        // Product images (read-only)
        Route::get('/products_images', [\App\Http\Controllers\api\Product_imageController::class, 'index'])->name('products_images.index');
        Route::get('/products_images/{id}', [\App\Http\Controllers\api\Product_imageController::class, 'detailsProductImage'])->name('products_images.show');

        // // Banks (read-only)
        // Route::get('/banks', [\App\Http\Controllers\api\BankController::class, 'getBank'])->name('banks.index');
        // Route::get('/banks/{id}', [\App\Http\Controllers\api\BankController::class, 'detailsBank'])->name('banks.show');

        // Warranties (read-only)
        Route::get('/warranties', [\App\Http\Controllers\api\WarrantyController::class, 'index'])->name('warranties.index');
        Route::get('/warranties/{id}', [\App\Http\Controllers\api\WarrantyController::class, 'show'])->name('warranties.show');

        // Slide show (read-only)
        Route::get('/slide_show', [\App\Http\Controllers\api\Slide_showController::class, 'index'])->name('slide_show.index');
        Route::get('/slide_show/{id}', [\App\Http\Controllers\api\Slide_showController::class, 'show'])->name('slide_show.show');

        // Slide show images (read-only)
        Route::get('/slide_show_images', [\App\Http\Controllers\api\SlideShowImageController::class, 'index'])->name('slide_show_image.index');
        Route::get('/slide_show_images/{id}', [\App\Http\Controllers\api\SlideShowImageController::class, 'show'])->name('slide_show_image.show');

        // Stocks (read-only)
        Route::get('/stocks', [\App\Http\Controllers\api\StockController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/{id}', [\App\Http\Controllers\api\StockController::class, 'show'])->name('stocks.show');

        // Company info (read-only)
        Route::get('/company_info', [\App\Http\Controllers\api\Company_infoController::class, 'index'])->name('company_info.index');
        Route::get('/company_info/{id}', [\App\Http\Controllers\api\Company_infoController::class, 'show'])->name('company_info.show');

        // OTP (read-only — consider whether clients should really see this endpoint at all)
        Route::get('/otp', [\App\Http\Controllers\api\OtpsController::class, 'index'])->name('otp.index');
        Route::get('/otp/{id}', [\App\Http\Controllers\api\OtpsController::class, 'show'])->name('otp.show');

        // Customers (own data — see note below)
        Route::get('/customers', [\App\Http\Controllers\api\CustomerController::class, 'getAllCustomers'])->name('customers.index');
        Route::get('/customers/{id}', [\App\Http\Controllers\api\CustomerController::class, 'getCustomerDetails'])->name('customers.show');

        // Orders — full CRUD for the client's own orders
        Route::get('/orders', [\App\Http\Controllers\api\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\api\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders', [\App\Http\Controllers\api\OrderController::class, 'store'])->name('orders.store');
        Route::put('/orders/{id}', [\App\Http\Controllers\api\OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{id}', [\App\Http\Controllers\api\OrderController::class, 'destroy'])->name('orders.destroy');

        //payment routes for clients (user role)
        Route::get('/payments', [\App\Http\Controllers\api\PaymentController::class, 'getAllPayments'])->name('payments.index');

        // Initiate a Bakong payment
        Route::post('/payments/bakong/create', [\App\Http\Controllers\api\PaymentController::class, 'createBakongPayment'])->name('payments.bakong.create');

        // Check Bakong payment status (polling)
        Route::get('/payments/{id}/check-status', [\App\Http\Controllers\api\PaymentController::class, 'checkPaymentStatus'])->name('payments.bakong.check-status');

        // User images — full CRUD for the client's own uploaded images
        Route::get('/user_images', [\App\Http\Controllers\api\User_imageController::class, 'index'])->name('user_images.index');
        Route::get('/user_images/{id}', [\App\Http\Controllers\api\User_imageController::class, 'show'])->name('user_images.show');
        Route::post('/user_images', [\App\Http\Controllers\api\User_imageController::class, 'store'])->name('user_images.store');
        Route::put('/user_images/{id}', [\App\Http\Controllers\api\User_imageController::class, 'update'])->name('user_images.update');
        Route::delete('/user_images/{id}', [\App\Http\Controllers\api\User_imageController::class, 'destroy'])->name('user_images.destroy');

    });

});