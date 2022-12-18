<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;





Route::post('/login', [AuthController::class, 'signin'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('signup');


     

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
