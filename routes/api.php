<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/validate', [AuthController::class, 'validateUser'])->middleware('auth:api');
    //Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
});

Route::get('/test', function () {
    return response()->json(['message' => 'This is a test route']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('/books', [BookController::class, 'search']);
    Route::post('/rentals', [RentalController::class, 'store']);
    Route::post('/rentals/return', [RentalController::class, 'returnBook']);
    Route::get('/rentals/history', [RentalController::class, 'rentalHistory']);
    Route::post('/rentals/stats', [RentalController::class, 'rentalStats']);
});

