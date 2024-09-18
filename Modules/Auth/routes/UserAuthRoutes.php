<?php


use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\User\UserSignupController;


Route::prefix('user')->group(function () {

    Route::post('signup', [UserSignupController::class, 'userSignup']);

});

