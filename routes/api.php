<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\AuthController;

//Registre et Login BUSSINES START EVALUATOR
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class,'loginUser']);
Route::post('/registre', [UserController::class,'createUser']);
Route::get('/login/{provider}', [UserController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [UserController::class,'handleProviderCallback']);

Route::group(['middleware'=> 'auth:sanctum'],function(){
    Route::get('user',[UserController::class,'userDetails']);
    Route::get('logout',[UserController::class, 'logout']);
});*/


//google auth Route

Route::get('/', function (){
    return view('welcome');
});

Route::get('/auth/google', [GoogleController::class,'loginWithGoogle'])->middleware(Spatie\Csp\AddCspHeaders::class);
Route::any('/auth/google/callback',[GoogleController::class,'callbackFromGoogle'])->name('callback');

// Route::get('login', [AuthController::class,'index'])->name('login');
// Route::get('registration', [AuthController::class,'registration'])->name('registration');
Route::post('registration', [AuthController::class,'postRegistration'])->name('registration.post');
Route::post('post-login', [AuthController::class,'postLogin'])->name('login.post');


Route::get('home', function(){
    return view('home');
})->name('home');


// //test route
// Route::group(['prefix' => '/auth', ['middleware' => 'throttle:20,5']], function() {
//     Route::post('/register', 'Auth\RegisterController@register');
//     Route::post('/login', 'Auth\LoginController@login');

//     Route::get('/login/{service}', 'Auth\SocialLoginController@redirect');
//     Route::get('/login/{service}/callback', 'Auth\SocialLoginController@callback');
// });

// Route::group(['middleware' => 'jwt.auth'], function() {
//     Route::get('/me', 'MeController@index');

//     Route::get('/auth/logout', 'MeController@logout');
// });

// Route::controller(AuthController::class)->group(function(){
//     Route::post('login', 'login');
// });
