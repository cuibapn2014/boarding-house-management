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
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
    Route::get('/dang-ky', [RegisterController::class, 'create'])->name('register');
    Route::post('/dang-ky', [RegisterController::class, 'store'])->name('register.perform');
    
    // Social Authentication
    Route::get('/auth/google', [\App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/dang-xuat', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// User Profile Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/tai-khoan', [UserProfileController::class, 'update'])->name('profile.update');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('gioi-thieu', [\App\Http\Controllers\AboutController::class, 'index'])->name('about.index');
Route::get('chinh-sach-bao-mat', [\App\Http\Controllers\PrivacyController::class, 'index'])->name('privacy.index');
Route::get('lien-he', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('lien-he', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::group(['prefix' => 'danh-sach-cho-thue'], function () {
	Route::get('/', [\App\Http\Controllers\RentalHomeController::class, 'index'])->name('rentalHome.index');
	Route::get('/{id}/{title}', [\App\Http\Controllers\RentalHomeController::class, 'show'])->name('rentalHome.show');
});

Route::post('/{id}/{title}/create-appointment', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointment.store');

// Saved Listings Routes
Route::group(['prefix' => 'tin-da-luu', 'middleware' => 'auth'], function () {
	Route::get('/', [\App\Http\Controllers\SavedListingController::class, 'index'])->name('savedListings.index');
});

Route::group(['prefix' => 'api/saved-listings'], function () {
	Route::post('/toggle', [\App\Http\Controllers\SavedListingController::class, 'toggle'])->name('savedListings.toggle');
	Route::post('/store', [\App\Http\Controllers\SavedListingController::class, 'store'])->name('savedListings.store');
	Route::delete('/{boardingHouseId}', [\App\Http\Controllers\SavedListingController::class, 'destroy'])->name('savedListings.destroy');
	Route::get('/check/{boardingHouseId}', [\App\Http\Controllers\SavedListingController::class, 'check'])->name('savedListings.check');
});