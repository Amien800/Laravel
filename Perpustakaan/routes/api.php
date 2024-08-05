<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\AutheController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RolesController;
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

Route::prefix('v1')->group(function () {
    Route::resource('role', RolesController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('book', BooksController::class);
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AutheController::class, 'register']);
        Route::post('/generate-otp-code', [
            AutheController::class,
            'otpCode',
        ])->middleware('auth:api');
        Route::post('/validate', [
            AutheController::class,
            'validation',
        ])->middleware('auth:api');
        Route::post('/login', [AutheController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('auth/logout', [AutheController::class, 'logout']);
        Route::get('me', [AutheController::class, 'me']);
        Route::get('index', [AutheController::class, 'index'])->middleware(
            'isAdmin'
        );
        Route::post('profile', [ProfileController::class, 'store'])->middleware(
            'isVerificationAccount'
        );
        Route::resource('borrow', BorrowController::class)->middleware([
            'isVerificationAccount',
            'auth:api',
        ]);
    });
});
