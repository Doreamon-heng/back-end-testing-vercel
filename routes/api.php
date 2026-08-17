<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\Category_imageController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\Company_infoController;
use App\Http\Controllers\api\CustomerController;
// use App\Http\Controllers\api\DeliveryController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\OtpsController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\PostImageController;
use App\Http\Controllers\api\Product_imageController;
// use App\Http\Controllers\api\Product_statusController;
use App\Http\Controllers\api\ProductController;
// use App\Http\Controllers\api\Role_userController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\Slide_showController;
use App\Http\Controllers\api\SlideShowImageController;
use App\Http\Controllers\api\StockController;
use App\Http\Controllers\api\User_imageController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\WarrantyController;


Route::prefix('v1')->name('v1.')->group(function () {

    // =========================
    // PUBLIC
    // =========================

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/recovery-account', [AuthController::class, 'recoveryAccount'])->name('recovery.account');
    Route::post('/recovery-account-by-phone', [AuthController::class, 'recoveryAccountByPhone'])
        ->name('recovery.account.phone');

    Route::post('/bakong/webhook', [PaymentController::class, 'webhook'])
        ->name('bakong.webhook');


    // =========================
    // AUTHENTICATED
    // =========================

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');


        // =========================
        // SHARED READ-ONLY
        // Admin + User
        // =========================

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/{id}', [ProductController::class, 'show'])
            ->name('products.show');

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/{id}', [CategoryController::class, 'show'])
            ->name('categories.show');

        Route::get('/categories-images', [Category_imageController::class, 'getAllCateImage'])
            ->name('categories-images.index');

        Route::get('/categories-images/{id}', [Category_imageController::class, 'detailsCateImage'])
            ->name('categories-images.show');

        Route::get('/products-images', [Product_imageController::class, 'index'])
            ->name('products-images.index');

        Route::get('/products-images/{id}', [Product_imageController::class, 'detailsProductImage'])
            ->name('products-images.show');

        Route::get('/warranties', [WarrantyController::class, 'index'])
            ->name('warranties.index');

        Route::get('/warranties/{id}', [WarrantyController::class, 'show'])
            ->name('warranties.show');

        Route::get('/slide-shows', [Slide_showController::class, 'index'])
            ->name('slide-shows.index');

        Route::get('/slide-shows/{id}', [Slide_showController::class, 'show'])
            ->name('slide-shows.show');

        Route::get('/slide-show-images', [SlideShowImageController::class, 'index'])
            ->name('slide-show-images.index');

        Route::get('/slide-show-images/{id}', [SlideShowImageController::class, 'show'])
            ->name('slide-show-images.show');

        Route::get('/stocks', [StockController::class, 'index'])
            ->name('stocks.index');

        Route::get('/stocks/{id}', [StockController::class, 'show'])
            ->name('stocks.show');

        Route::get('/company-info', [Company_infoController::class, 'index'])
            ->name('company-info.index');

        Route::get('/company-info/{id}', [Company_infoController::class, 'show'])
            ->name('company-info.show');


        // =========================
        // ADMIN ONLY
        // =========================

        Route::middleware('admin')->group(function () {

            // Roles
            Route::get('/roles', [RoleController::class, 'index'])
                ->name('roles.index');

            Route::get('/roles/{id}', [RoleController::class, 'show'])
                ->name('roles.show');

            Route::post('/roles', [RoleController::class, 'store'])
                ->name('roles.store');

            Route::put('/roles/{id}', [RoleController::class, 'update'])
                ->name('roles.update');

            Route::delete('/roles/{id}', [RoleController::class, 'destroy'])
                ->name('roles.destroy');


            // Users
            Route::get('/users', [UserController::class, 'getAllUsers'])
                ->name('users.index');

            Route::post('/users', [UserController::class, 'createUser'])
                ->name('users.store');

            Route::get('/users/{id}', [UserController::class, 'getUserById'])
                ->name('users.show');

            Route::put('/users/{id}', [UserController::class, 'updateUser'])
                ->name('users.update');

            Route::delete('/users/{id}', [UserController::class, 'deleteUser'])
                ->name('users.destroy');


            // Customers
            Route::get('/customers', [CustomerController::class, 'getAllCustomers'])
                ->name('customers.index');

            Route::post('/customers', [CustomerController::class, 'createCustomer'])
                ->name('customers.store');

            Route::get('/customers/{id}', [CustomerController::class, 'getCustomerDetails'])
                ->name('customers.show');

            Route::put('/customers/{id}', [CustomerController::class, 'updateCustomer'])
                ->name('customers.update');

            Route::delete('/customers/{id}', [CustomerController::class, 'deleteCustomer'])
                ->name('customers.destroy');


            // Categories CRUD
            Route::post('/categories', [CategoryController::class, 'store'])
                ->name('categories.store');

            Route::put('/categories/{id}', [CategoryController::class, 'update'])
                ->name('categories.update');

            Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
                ->name('categories.destroy');


            // Products CRUD
            Route::post('/products', [ProductController::class, 'create'])
                ->name('products.create');

            Route::put('/products/{id}', [ProductController::class, 'update'])
                ->name('products.update');

            Route::delete('/products/{id}', [ProductController::class, 'destroy'])
                ->name('products.destroy');


            // Product Images CRUD
            Route::post('/products-images', [Product_imageController::class, 'createProductImage'])
                ->name('products-images.store');

            Route::put('/products-images/{id}', [Product_imageController::class, 'updateProductImage'])
                ->name('products-images.update');

            Route::delete('/products-images/{id}', [Product_imageController::class, 'deleteProductImage'])
                ->name('products-images.destroy');


            // Warranties CRUD
            Route::post('/warranties', [WarrantyController::class, 'store'])
                ->name('warranties.store');

            Route::put('/warranties/{id}', [WarrantyController::class, 'update'])
                ->name('warranties.update');

            Route::delete('/warranties/{id}', [WarrantyController::class, 'destroy'])
                ->name('warranties.destroy');


            // OTP
            Route::get('/otp', [OtpsController::class, 'index'])
                ->name('otp.index');

            Route::get('/otp/{id}', [OtpsController::class, 'show'])
                ->name('otp.show');

            Route::post('/otp', [OtpsController::class, 'store'])
                ->name('otp.store');

            Route::put('/otp/{id}', [OtpsController::class, 'update'])
                ->name('otp.update');

            Route::delete('/otp/{id}', [OtpsController::class, 'destroy'])
                ->name('otp.destroy');


            // Slide Shows CRUD
            Route::post('/slide-shows', [Slide_showController::class, 'store'])
                ->name('slide-shows.store');

            Route::put('/slide-shows/{id}', [Slide_showController::class, 'update'])
                ->name('slide-shows.update');

            Route::delete('/slide-shows/{id}', [Slide_showController::class, 'destroy'])
                ->name('slide-shows.destroy');


            // Slide Show Images CRUD
            Route::post('/slide-show-images', [SlideShowImageController::class, 'store'])
                ->name('slide-show-images.store');

            Route::put('/slide-show-images/{id}', [SlideShowImageController::class, 'update'])
                ->name('slide-show-images.update');

            Route::delete('/slide-show-images/{id}', [SlideShowImageController::class, 'destroy'])
                ->name('slide-show-images.destroy');


            // Stocks CRUD
            Route::post('/stocks', [StockController::class, 'store'])
                ->name('stocks.store');

            Route::put('/stocks/{id}', [StockController::class, 'update'])
                ->name('stocks.update');

            Route::delete('/stocks/{id}', [StockController::class, 'destroy'])
                ->name('stocks.destroy');


            // Company Info CRUD
            Route::post('/company-info', [Company_infoController::class, 'store'])
                ->name('company-info.store');

            Route::put('/company-info/{id}', [Company_infoController::class, 'update'])
                ->name('company-info.update');

            Route::delete('/company-info/{id}', [Company_infoController::class, 'destroy'])
                ->name('company-info.destroy');


            // Category Images CRUD
            Route::post('/categories-images', [Category_imageController::class, 'createCateImage'])
                ->name('categories-images.store');

            Route::put('/categories-images/{id}', [Category_imageController::class, 'updateCateImage'])
                ->name('categories-images.update');

            Route::delete('/categories-images/{id}', [Category_imageController::class, 'destroyCateImage'])
                ->name('categories-images.destroy');


            // Post CRUD
            Route::get('/posts', [PostController::class, 'index'])
                ->name('posts.index');

            Route::get('/posts/{id}', [PostController::class, 'details'])
                ->name('posts.show');

            Route::post('/posts', [PostController::class, 'store'])
                ->name('posts.store');

            Route::put('/posts/{id}', [PostController::class, 'update'])
                ->name('posts.update');

            Route::delete('/posts/{id}', [PostController::class, 'destroy'])
                ->name('posts.destroy');


            // Post Images
            Route::get('/post-images', [PostImageController::class, 'index'])
                ->name('post-images.index');

            Route::get('/post-images/{id}', [PostImageController::class, 'details'])
                ->name('post-images.show');

            Route::post('/post-images', [PostImageController::class, 'store'])
                ->name('post-images.store');

            Route::put('/post-images/{id}', [PostImageController::class, 'update'])
                ->name('post-images.update');

            Route::delete('/post-images/{id}', [PostImageController::class, 'destroy'])
                ->name('post-images.destroy');
        });


        // =========================
        // USER / CLIENT ONLY
        // =========================

        Route::middleware('user')->group(function () {

            Route::get('/profile', [UserController::class, 'getProfile'])
                ->name('profile.show');

            Route::put('/profile', [UserController::class, 'updateProfile'])
                ->name('profile.update');

            Route::delete('/profile', [UserController::class, 'deleteProfile'])
                ->name('profile.destroy');


            // Orders
            Route::get('/orders', [OrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/orders/{id}', [OrderController::class, 'show'])
                ->name('orders.show');

            Route::post('/orders', [OrderController::class, 'store'])
                ->name('orders.store');

            Route::put('/orders/{id}', [OrderController::class, 'update'])
                ->name('orders.update');

            Route::delete('/orders/{id}', [OrderController::class, 'destroy'])
                ->name('orders.destroy');


            // Payments
            Route::get('/payments', [PaymentController::class, 'getAllPayments'])
                ->name('payments.index');

            Route::post('/payments/bakong/create', [PaymentController::class, 'createBakongPayment'])
                ->name('payments.bakong.create');

            Route::get('/payments/{id}/check-status', [PaymentController::class, 'checkPaymentStatus'])
                ->name('payments.bakong.check-status');


            // User Images
            Route::get('/user-images', [User_imageController::class, 'index'])
                ->name('user-images.index');

            Route::get('/user-images/{id}', [User_imageController::class, 'show'])
                ->name('user-images.show');

            Route::post('/user-images', [User_imageController::class, 'store'])
                ->name('user-images.store');

            Route::put('/user-images/{id}', [User_imageController::class, 'update'])
                ->name('user-images.update');

            Route::delete('/user-images/{id}', [User_imageController::class, 'destroy'])
                ->name('user-images.destroy');
        });
    });
});