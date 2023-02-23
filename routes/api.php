<?php

namespace App\Http\Controllers\Api;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', [ApiController::class, 'login']);

Route::middleware('jwt.auth')->prefix('auth')->group(function() {
    Route::post('profile', [ApiController::class, 'profile']);
    Route::post('logout', [ApiController::class, 'logout']);

    // borrower
    Route::prefix('borrower')->group(function() {
        Route::post('create', [BorrowerController::class, 'borrowerCreate']);
        Route::get('list', [BorrowerController::class, 'borrowerList']);
        Route::get('detail/{appId}', [BorrowerController::class, 'borrowerDetail']);
    });

    // agreement
    Route::prefix('agreement')->group(function() {
        Route::get('list', [AgreementController::class, 'agreementList']);
        Route::post('download', [AgreementController::class, 'agreementDownload']);
    });
});
