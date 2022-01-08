<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/login', [AuthController::class, 'login']);

//Route::get('/posts/latest/{id}', [PostController::class, 'latest']);

Route::group(["middleware" => ["auth:sanctum"]], function(){
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/auth/check', [AuthController::class, 'check']);
    Route::get('/friends/suggest/{id}', [FriendController::class, 'suggest']);
    Route::post('/friends/request/{user}', [FriendController::class, 'addRequest']);
    Route::resource('/posts', PostController::class);
    Route::get('/posts/latest/{id}', [PostController::class, 'latest']);
    Route::post('/my-posts', [PostController::class, 'myPosts']);
    Route::post('/user/{user}', [UserController::class, 'update']);
});


//PASSWORD RESET ROUTES SECTION

//display password reset page
Route::get('/forgot-password', function () {
    return redirect('http://localhost:8080/resetpwd');
})->middleware('guest')->name('password.request');

//receive email address and send link to user
Route::post('/forgot-password', [AuthController::class, 'sendResetEmail'])->middleware('guest')->name('password.email');

//after user clicks on reset link, show the reset form
// Route::get('/reset-password/{token}', function (Request $request, $token) {
//     return redirect('http://localhost:8080/resetpwd?token='. $token . '&email=' .  $request->input('email')); //['token' => $token, 'message' =>'', 'success' => 1];
// })->middleware('guest')->name('password.reset');

//reset with submitted password

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');
