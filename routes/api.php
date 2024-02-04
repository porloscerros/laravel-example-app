<?php

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
/*****************
 * Greetings
 *****************/
Route::get('/', function () {
    return response()->json([
        'greetings' => 'Welcome to '.config('app.name').' API',
        'authenticate' => url('api/login'),
        'current_api_version' => config('app.current_api_version', 1),
    ]);
})->name('greetings');

require __DIR__.'/auth-api.php';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*****************
 * API v1
 *****************/
Route::group(['prefix' => 'v1'], function () {
    /*****************
     * Public API
     *****************/

    /*****************
     * Private API
     *****************/
    Route::middleware('auth:sanctum')->group(function () {

    });
});
