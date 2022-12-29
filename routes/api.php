<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\SubFeatureController;
use App\Http\Controllers\TicketController;

Route::post('login', [AuthController::class, 'signin'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::post('check-data', [CheckDataEmployeeController::class, 'checkEmployee'])->name('auth.checkdata');


Route::middleware('auth:api')->group( function () {

    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('auth.forgotpassword');
    
    
    //feature
    Route::post('add-feature', [FeatureController::class, 'store'])->name('auth.addFeature');
    Route::post('update-feature/{id}', [FeatureController::class, 'update'])->name('auth.updateFeature');
    Route::delete('delete-feature/{id}', [FeatureController::class, 'destroy'])->name('auth.deleteFeature');
    Route::delete('delete-all-feature', [FeatureController::class, 'destroyAll'])->name('auth.deleteAllFeature');
    Route::get('get-feature', [FeatureController::class, 'getFeature'])->name('auth.getFeature');
    Route::get('get-feature/{id}', [FeatureController::class, 'show'])->name('auth.getFeatureById');


    //sub feature
    Route::post('add-sub-feature', [SubFeatureController::class, 'store'])->name('auth.addSubFeature');
    Route::post('update-sub-feature/{id}', [SubFeatureController::class, 'update'])->name('auth.updateSubFeature');
    Route::delete('delete-sub-feature/{id}', [SubFeatureController::class, 'destroy'])->name('auth.deleteSubFeature');
    Route::delete('delete-all-sub-feature', [SubFeatureController::class, 'destroyAll'])->name('auth.deleteAllSubFeature');
    Route::get('get-sub-feature', [SubFeatureController::class, 'getSubFeature'])->name('auth.getSubFeature');
    Route::get('get-sub-feature/{id}', [SubFeatureController::class, 'show'])->name('auth.getSubFeatureById');

    //Ticket
    Route::post('add-ticket', [TicketController::class, 'store'])->name('auth.addTicket');
    Route::post('update-ticket/{id}', [TicketController::class, 'update'])->name('auth.updateTicket');
    Route::delete('delete-ticket/{id}', [TicketController::class, 'destroy'])->name('auth.deleteTicket');
    Route::get('get-ticket', [TicketController::class, 'getTicket'])->name('auth.getTicket');
    Route::get('get-ticket/{id}', [TicketController::class, 'show'])->name('auth.getTicketById');

    
    Route::get('data', [AuthController::class, 'data'])->name('auth.data');

});