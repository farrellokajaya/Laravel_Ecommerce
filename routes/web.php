<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/',[UserController::class,'home'])->name('index');
Route::get('/dashboard',[UserController::class,'index'])->middleware
(['auth', 'verified'])->name('dashboard');
Route::get('/myorders',[UserController::class,'myOrders'])->middleware
(['auth', 'verified'])->name('myorders');
Route::get('/product_details/{id}',[UserController::class,'productDetails'])->name('product_details');
Route::get('/allproducts',[UserController::class,'allProducts'])->name('viewallproducts');
Route::get('/addtocart/{id}',[UserController::class,'addToCart'])->middleware
(['auth', 'verified'])->name('add_to_cart');
Route::get('/cartproduct',[UserController::class,'cartProduct'])->middleware
(['auth', 'verified'])->name('cartproduct');
Route::get('/removecartproducts/{id}',[UserController::class,'removeCartProducts'])->middleware
(['auth', 'verified'])->name('removecartproducts');
Route::post('/confirm_order',[UserController::class,'confirmOrder'])->middleware
(['auth', 'verified'])->name('confirm_order');
Route::controller(UserController::class)->middleware(['auth', 'verified'])->group(function(){

    Route::get('stripe/{price}', 'stripe')->name('stripe');

    Route::post('stripe', 'stripePost')->name('stripe.post');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('admin')->group(function () {
    Route::get('/add_category', [AdminController::class, 'addCategory'])->name('admin.addcategory');
    Route::post('/add_category', [AdminController::class, 'postAddCategory'])->name('admin.postaddcategory');
    Route::get('/view_category', [AdminController::class, 'viewCategory'])->name('admin.viewcategory');
    Route::get('/delete_category/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categorydelete');
    Route::get('/update_category/{id}', [AdminController::class, 'updateCategory'])->name('admin.categoryupdate');
    Route::post('/update_category/{id}', [AdminController::class, 'postUpdateCategory'])->name('admin.postupdatecategory');
    Route::get('/add_product', [AdminController::class, 'addProduct'])->name('admin.addproduct');
    Route::post('/add_product', [AdminController::class, 'postAddProduct'])->name('admin.postaddproduct');
    Route::get('/view_product', [AdminController::class, 'viewProduct'])->name('admin.viewproduct');
    Route::get('/deleteproduct/{id}', [AdminController::class, 'deleteProduct'])->name('admin.deleteproduct');
    Route::get('/updateproduct/{id}', [AdminController::class, 'updateProduct'])->name('admin.updateproduct');
    Route::post('/updateproduct/{id}', [AdminController::class, 'postUpdateProduct'])->name('admin.postupdateproduct');
    Route::any('/search', [AdminController::class, 'searchProduct'])->name('admin.searchproduct');
    Route::get('/vieworder', [AdminController::class, 'viewOrder'])->name('admin.vieworder');
    Route::post('/change_status/{id}', [AdminController::class, 'changeStatus'])->name('admin.change_status');
    Route::get('/downloadpdf/{id}', [AdminController::class, 'downloadPDF'])->name('admin.downloadpdf');
});


require __DIR__.'/auth.php';
