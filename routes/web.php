<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CasinoController,
    CategoryController,
	DashboardController,
	LoginController,
	PostController,
	ProfileController,
    UserController
};

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

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('login', [LoginController::class, 'loginSubmit'])->name('login.submit');

Route::middleware('admin.auth')->group(function () {

    Route::get('run-command', function () {
        Artisan::call('make:controller API/APIController');
//        Artisan::call('optimize:clear');

        $output = Artisan::output();

        return "Artisan Command Executed: your:artisan-command\n" . $output;
    });

	Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

	Route::get('post', [PostController::class, 'index'])->name('post');
	Route::get('post/create', [PostController::class, 'create'])->name('post.create');
	Route::post('post/store', [PostController::class, 'store'])->name('post.store');
	Route::get('post/edit/{id}', [PostController::class, 'edit'])->name('post.edit');
	Route::patch('post/update/{id}', [PostController::class, 'update'])->name('post.update');
	Route::get('post/delete/{id}', [PostController::class, 'destroy'])->name('post.delete');

	Route::get('category', [CategoryController::class, 'index'])->name('category');
	Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
	Route::post('category/store', [CategoryController::class, 'store'])->name('category.store');
	Route::get('category/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
	Route::patch('category/update/{category}', [CategoryController::class, 'update'])->name('category.update');
	Route::get('category/delete/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
	
	Route::get('casino', [CasinoController::class, 'index'])->name('casino');
	Route::get('casino/create', [CasinoController::class, 'create'])->name('casino.create');
	Route::post('casino/store', [CasinoController::class, 'store'])->name('casino.store');
	Route::get('casino/edit/{casino}', [CasinoController::class, 'edit'])->name('casino.edit');
	Route::patch('casino/update/{casino}', [CasinoController::class, 'update'])->name('casino.update');
	Route::get('casino/delete/{casino}', [CasinoController::class, 'destroy'])->name('casino.delete');

	Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
	Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
	
	Route::get('user', [UserController::class, 'index'])->name('user');

	Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});
