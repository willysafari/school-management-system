<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


route::post('/register',[AuthController::class,'register'])->name('register');
route::post('/login',[AuthController::class,'login'])->name('login');

route::group(['middleware'=>'auth:sanctum'], function(){
   route::get('/profile',[AuthController::class,'profile'])->name('profile');
   route::get('/logout',[AuthController::class,'logout'])->name('logout');

   route::apiResource('categories', SchoolCategoryController::class);

});

