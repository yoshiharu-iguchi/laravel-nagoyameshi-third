<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Subscribed;
use App\Http\Middleware\NotSubscribed;
use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TermController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// 管理者以外のトップページ
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');


// 一般ユーザー向け：店舗の閲覧（一覧・詳細）
Route::resource('restaurants', RestaurantController::class)->only(['index', 'show']);

Route::get('company',[CompanyController::class,'index'])->name('company.index');
Route::get('terms',[TermController::class,'index'])->name('terms.index');

// 一般ユーザー向け：認証済ユーザー
Route::middleware(['auth', 'verified'])->group(function () {
    // ユーザー情報の管理
    Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);

    Route::resource('restaurants.reviews',ReviewController::class)->only(['index']);

    // 未加入者向けサブスクリプションルート
    Route::middleware([NotSubscribed::class])->group(function () {
        Route::get('subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
        Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
    });

    // 有料会員向けサブスクリプションルート
    Route::middleware([Subscribed::class])->group(function () {
        Route::get('subscription/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
        Route::patch('subscription', [SubscriptionController::class, 'update'])->name('subscription.update');
        Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::delete('subscription', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');

        Route::resource('restaurants.reviews',ReviewController::class)->only(['create','store','edit','update','destroy']);

        Route::resource('reservations',ReservationController::class)->only(['index','destroy']);
        Route::resource('restaurants.reservations',ReservationController::class)->only(['create','store']);

        Route::get('favorites',[FavoriteController::class,'index'])->name('favorites.index');
        Route::post('favorites/{restaurant_id}',[FavoriteController::class,'store'])->name('favorites.store');
        Route::delete('favorites/{restaurant_id}',[FavoriteController::class,'destroy'])->name('favorites.destroy');
    });
   });
});

// Laravel Breezeなどの認証ルート
require __DIR__ . '/auth.php';

// 管理者向けルート
Route::prefix('admin')->as('admin.')->middleware('auth:admin')->group(function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::resource('users', Admin\UserController::class)->only(['index', 'show']);
    Route::resource('restaurants', Admin\RestaurantController::class);
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('company', Admin\CompanyController::class)->only(['index', 'edit', 'update']);
    Route::resource('terms', Admin\TermController::class)->only(['index', 'edit', 'update']);
});








