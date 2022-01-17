<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocalizationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'index']);

Route::get('/home', [PageController::class, 'index']);

Route::get('/about',[PageController::class, 'about']);

Route::get('/contact',[ContactController::class, 'contact']);

Route::post('/contact/send', [ContactController::class, 'send']);

Route::get('/profile',[ProfileController::class, 'index']);

Route::put('/profile',[ProfileController::class, 'edit']);

Route::put('/profile/avatar',[ProfileController::class, 'edit_avatar']);

Route::get('/products/all', [ProductController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/products/category/{id}', [ProductController::class, 'category']);

Route::get('/products/search/{keyword}', [ProductController::class, 'search']);

Route::get('/cart', [CartController::class, 'index']);

Route::post('/cart/{product_id}', [CartController::class, 'add']);

Route::delete('/cart/{id}', [CartController::class, 'delete']);

Route::delete('/cart', [CartController::class, 'deleteAll']);

Route::put('/cart/{id}', [CartController::class, 'update']);

Route::get('/payment', [PaymentController::class, 'index']);

Route::post('/payment/transaction', [PaymentController::class, 'makePayment'])->name('make-payment');

Route::get('/payment/history', [PaymentController::class, 'history']);

Route::post('/command/all', [CommandController::class, 'add_all']);

Route::post('/command/{id}', [CommandController::class, 'add']);

Route::post('/command/buynow/{id}', [CommandController::class, 'buy_now']);

Route::put('/command/{id}', [CommandController::class, 'update']);

Route::delete('/command/{id}', [CommandController::class, 'delete']);

Route::post('/review/{product_id}',[ReviewController::class,'add']);

Route::delete('/review/{review_id}',[ReviewController::class,'delete']);

Route::put('/review/{review_id}',[ReviewController::class,'edit']);

Route::get('/lang/{locale}',[LocalizationController::class,'index']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();
