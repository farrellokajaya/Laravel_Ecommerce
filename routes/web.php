<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\DashboardController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\InvoiceController;
use App\Http\Controllers\Storefront\OrderController;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

use Illuminate\Support\Facades\Route;


/* Public Routes */

Route::get('/',[HomeController::class,'index'])->name('home');

Route::get('/product_details/{id}',[StorefrontProductController::class,'show'])->name('product_details');

Route::get('/allproducts',[StorefrontProductController::class,'index'])->name('viewallproducts');

/* Auth User Routes */

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    Route::get('/myorders', [OrderController::class, 'index'])
        ->name('myorders');

    Route::post('/addtocart/{id}',[CartController::class,'store'])
        ->name('add_to_cart');
    
    Route::get('/cartproduct',[CartController::class,'index'])
        ->name('cartproduct');
    
    Route::delete('/removecartproducts/{id}',[CartController::class,'destroy'])
        ->name('removecartproducts');
    
    Route::get('/checkout', [CheckoutController::class, 'show'])
        ->name('checkout');

    Route::post('/checkout/payment', [CheckoutController::class, 'store'])
        ->name('checkout.payment');
    
     Route::get('/payment/success/{invoiceNumber}', [CheckoutController::class,'success'])
        ->name('payment.success');

    Route::get('/invoice/{invoiceNumber}/download', [InvoiceController::class,'download'])
        ->name('invoice.download');
});

/* Profile Routes */
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/* Admin Routes */

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/add_category', [CategoryController::class, 'create'])
        ->name('admin.addcategory');

    Route::post('/add_category', [CategoryController::class, 'store'])
        ->name('admin.postaddcategory');

    Route::get('/view_category', [CategoryController::class, 'index'])
        ->name('admin.viewcategory');

    Route::delete('/delete_category/{id}', [CategoryController::class, 'destroy'])
        ->name('admin.categorydelete');

    Route::get('/update_category/{id}', [CategoryController::class, 'edit'])
        ->name('admin.categoryupdate');

    Route::post('/update_category/{id}', [CategoryController::class, 'update'])
        ->name('admin.postupdatecategory');

    Route::get('/add_product', [AdminProductController::class, 'create'])
        ->name('admin.addproduct');

    Route::post('/add_product', [AdminProductController::class, 'store'])
        ->name('admin.postaddproduct');

    Route::get('/view_product', [AdminProductController::class, 'index'])
        ->name('admin.viewproduct');

    Route::delete('/deleteproduct/{id}', [AdminProductController::class, 'destroy'])
        ->name('admin.deleteproduct');

    Route::get('/updateproduct/{id}', [AdminProductController::class, 'edit'])
        ->name('admin.updateproduct');

    Route::post('/updateproduct/{id}', [AdminProductController::class, 'update'])
        ->name('admin.postupdateproduct');

    Route::get('/search', [AdminProductController::class, 'search'])
        ->name('admin.searchproduct');

    Route::get('/vieworder', [AdminOrderController::class, 'index'])
        ->name('admin.vieworder');

    Route::post('/change_status/{id}', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.change_status');

    Route::get('/downloadpdf/{id}', [AdminOrderController::class, 'downloadPdf'])
        ->name('admin.downloadpdf');
});


require __DIR__.'/auth.php';
