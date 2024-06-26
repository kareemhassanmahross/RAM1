<?php

use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\ImagesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\SupCategoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\SendCardEmailController;
use App\Http\Controllers\Auth\resetePasswordController;
use App\Http\Controllers\Auth\AdminUserController;
use App\Http\Controllers\SendGetTouchController;
use App\Http\Controllers\testController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'UserAdmin'], function () {
    Route::get('/', [AdminUserController::class, 'index']);
    Route::get('/{id}', [AdminUserController::class, 'ShowUserAdmin']);
    Route::post('/register', [AdminUserController::class, 'createAdmin']);
    Route::post('/login', [AdminUserController::class, 'loginUserAdmin']);
    Route::POST('/logout', [AdminUserController::class, 'logoutUser'])->middleware('auth:sanctum');
});
Route::group(['prefix' => 'User'], function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/{id}', [AuthController::class, 'ShowUser']);
    Route::post('/register', [AuthController::class, 'createUser']);
    Route::post('/login', [AuthController::class, 'loginUser']);
    Route::post('/logout', [AuthController::class, 'logoutUser'])->middleware('auth:sanctum');
});
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('test', [testController::class, 'index']);
});
// Route::group(['middleware' => 'auth:sanctum'], function () {

Route::post('/gettouch', [SendGetTouchController::class, 'getTouch']);
Route::post('/sendMail', [SendCardEmailController::class, 'SendEmail']);
Route::post('/resetPassword', [resetePasswordController::class, 'resetPassword']);
Route::post('/resetNewPassword', [resetePasswordController::class, 'newPassword']);
// });
// Route::group(['middleware' => 'auth:sanctum'], function () {
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/create', [CategoryController::class, 'create']);
    Route::post('/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
});
Route::group(['prefix' => 'SubCategory'], function () {
    Route::get('/', [SupCategoryController::class, 'index']);
    Route::get('/{id}', [SupCategoryController::class, 'show']);
    Route::post('/create', [SupCategoryController::class, 'create']);
    Route::post('/update/{id}', [SupCategoryController::class, 'update']);
    Route::delete('/delete/{id}', [SupCategoryController::class, 'destroy']);
});
Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/create', [ProductController::class, 'create']);
    Route::post('/update/{id}', [ProductController::class, 'update']);
    Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
});
Route::group(['prefix' => 'images'], function () {
    Route::get('/', [ImagesController::class, 'index']);
    Route::get('/{id}', [ImagesController::class, 'show']);
    Route::post('/create', [ImagesController::class, 'create']);
    Route::post('/update/{id}', [ImagesController::class, 'update']);
    Route::delete('/delete/{id}', [ImagesController::class, 'destroy']);
});
Route::group(['prefix' => 'slider'], function () {
    Route::get('/', [SliderController::class, 'index']);
    Route::get('/{id}', [SliderController::class, 'show']);
    Route::post('/create', [SliderController::class, 'create']);
    Route::post('/update/{id}', [SliderController::class, 'update']);
    Route::delete('/delete/{id}', [SliderController::class, 'destroy']);
});
Route::group(['prefix' => 'aboutus'], function () {
    Route::get('/', [AboutUsController::class, 'index']);
    Route::get('/{id}', [AboutUsController::class, 'show']);
    Route::post('/create', [AboutUsController::class, 'create']);
    Route::post('/update/{id}', [AboutUsController::class, 'update']);
    Route::delete('/delete/{id}', [AboutUsController::class, 'destroy']);
});
Route::group(['prefix' => 'socialmedia'], function () {
    Route::get('/', [SocialMediaController::class, 'index']);
    Route::get('/{id}', [SocialMediaController::class, 'show']);
    Route::post('/create', [SocialMediaController::class, 'create']);
    Route::post('/update/{id}', [SocialMediaController::class, 'update']);
    Route::delete('/delete/{id}', [SocialMediaController::class, 'destroy']);
});
Route::group(['prefix' => 'contactus'], function () {
    Route::get('/', [ContactUsController::class, 'index']);
    Route::get('/{id}', [ContactUsController::class, 'show']);
    Route::post('/create', [ContactUsController::class, 'create']);
    Route::post('/update/{id}', [ContactUsController::class, 'update']);
    Route::delete('/delete/{id}', [ContactUsController::class, 'destroy']);
});
// });
