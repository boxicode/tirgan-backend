<?php


use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\User\UserLoginController;
use Modules\Auth\App\Http\Controllers\User\UserProfileController;
use Modules\Auth\App\Http\Controllers\User\UserSignupController;


Route::prefix('user')->group(function () {

    // user Signup route
    Route::post('signup', [UserSignupController::class, 'userSignup']);

    // User login route
    Route::post('login', [UserLoginController::class, 'userLogin']);


    // verify user email route
    Route::get('signup/activation/{token}', [UserSignupController::class, 'emailSignupActive']);

    Route::middleware('auth:api')->group(function () {

        // User logout route
        Route::post('logout', [UserLoginController::class, 'UserLogout']);

        Route::get('profile', [UserProfileController::class, 'userProfile']);


    });

});

