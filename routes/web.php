<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
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

Route::post('/user-registration', [UserController::class , 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::post('/user-otp', [UserController::class, 'SendOTP']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);

// Reset Password

Route::post('/reset-password', [UserController::class, 'ResetPass'])->middleware([TokenVerificationMiddleware::class]);


// Page Routes

Route::get('/user-login', [UserController::class,  'Login']);
Route::get('/user-dashboard', [UserController::class, 'Dashboard']);
Route::get('/user-registration', [UserController::class, 'Registration']);
Route::get('/user-password-reset', [UserController::class, 'Password_Reset']);






























//Route::get('/',[HomeController::class,'HomePage']);
//Route::get('/userLogin',[UserController::class,'LoginPage']);
//Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
//Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
//Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
//Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/userProfile',[UserController::class,'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/categoryPage',[CategoryController::class,'CategoryPage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/customerPage',[CustomerController::class,'CustomerPage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/productPage',[ProductController::class,'ProductPage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/invoicePage',[InvoiceController::class,'InvoicePage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/salePage',[InvoiceController::class,'SalePage'])->middleware([TokenVerificationMiddleware::class]);
//Route::get('/reportPage',[ReportController::class,'ReportPage'])->middleware([TokenVerificationMiddleware::class]);
