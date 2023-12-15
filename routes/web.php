<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
//use App\Http\Controller\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\AuthController;

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

/*Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/auth/redirect', function () {
    return Socialite::driver('FACEBOOK')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('facebook')->user();
    dd($user);
    // $user->token
});

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('google')->user();
    dd($user);
    // $user->token
});*/

//google Route

Route::get('/', function (){
    return view('welcome');
});

Route::get('auth/google', [GoogleController::class,'loginWithGoogle'])->name('loginGoogle');
Route::any('auth/google/callback',[GoogleController::class,'callbackFromGoogle'])->name('callback');

Route::get('login', [AuthController::class,'index'])->name('login');
Route::get('registration', [AuthController::class,'registration'])->name('registration');
Route::post('post-registration', [AuthController::class,'postRegistration'])->name('registration.post');
Route::post('post-login', [AuthController::class,'postLogin'])->name('login.post');

Route::get('home', function(){
    return view('home');
})->name('home');

