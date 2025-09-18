<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SellController;

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

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register/store', [UserController::class, 'store'])->name('register.store');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
  Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.index');
  Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

  Route::post('/products/{product}/like', [LikeController::class, 'store'])->name('products.like');
  Route::delete('/products/{product}/like', [LikeController::class, 'destroy'])->name('products.unlike');

  Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('products.comments.store');

  Route::get('/sell', [SellController::class, 'index'])->name('sell.index');
  Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});
