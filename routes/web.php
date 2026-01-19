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
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
            

Route::get('/', function () {return redirect('/boarding-house');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->middleware('guest')->name('google.login');
	Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->middleware('guest')->name('google.callback');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	// Boarding House routes
	Route::get('/boarding-house/{id}/create-appointment', [\App\Http\Controllers\BoardingHouseController::class , 'createAppointment'])->name('boarding-house.createAppointment');
	Route::post('/boarding-house/{id}/create-appointment', [\App\Http\Controllers\BoardingHouseController::class, 'storeAppointment'])->name('boarding-house.storeAppointment');
	Route::resource('/boarding-house', \App\Http\Controllers\BoardingHouseController::class);
	Route::delete('/boarding-house-file/{id}/delete', [\App\Http\Controllers\BoardingHouseFileController::class, 'destroy'])->name('boardingHouseFile.destroy');
	
	// User Management routes
	Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
	Route::post('/user', [UserController::class, 'store'])->name('user.store');
	Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
	Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
	Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
	
	// Profile routes
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::post('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('profile.change-password');
	
	// Payment routes
	Route::get('/payment', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payment.index');
	Route::get('/payment/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
	Route::post('/payment', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payment.store');
	Route::get('/payment/{paymentCode}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
	Route::get('/payment/{paymentCode}/check-status', [\App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payment.checkStatus');
	Route::post('/payment/{paymentCode}/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
	
	// Point routes
	Route::get('/point/wallet', [\App\Http\Controllers\PointController::class, 'wallet'])->name('point.wallet');
	Route::get('/point/top-up', [\App\Http\Controllers\PointController::class, 'topUp'])->name('point.top-up');
	Route::post('/point/top-up', [\App\Http\Controllers\PointController::class, 'processTopUp'])->name('point.process-top-up');
	Route::get('/point/transactions', [\App\Http\Controllers\PointController::class, 'transactions'])->name('point.transactions');
	
	// Service Payment routes
	Route::get('/boarding-house/{boardingHouse}/services', [\App\Http\Controllers\ServicePaymentController::class, 'show'])->name('service-payment.show');
	Route::post('/boarding-house/{boardingHouse}/services/process', [\App\Http\Controllers\ServicePaymentController::class, 'process'])->name('service-payment.process');
	
	// Static pages
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
	
	// Logout
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
	
	// Page routes (must be last)
	Route::get('/{page}', [PageController::class, 'index'])->name('page.index');
});

Route::get('/hooks/sepay-payment-code/{paymentCode}', [\App\Http\Controllers\PaymentController::class, 'confirmWithCode'])->name('payment.confirmWithCode');
