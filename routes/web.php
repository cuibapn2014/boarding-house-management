<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
            

	// Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	// Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	// Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	// Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	// Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	// Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	// Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	// Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	// Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
// Route::group(['middleware' => 'auth'], function () {
	
// });

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('chinh-sach-bao-mat', [\App\Http\Controllers\PrivacyController::class, 'index'])->name('privacy.index');
Route::get('lien-he', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');

Route::group(['prefix' => 'danh-sach'], function() {
Route::get('/', [\App\Http\Controllers\RentalHomeController::class, 'index'])->name('rentalHome.index');
});