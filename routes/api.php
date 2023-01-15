<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\feedbackController;
use App\Http\Controllers\newsletterController;
use App\Http\Controllers\depositsController;
use App\Http\Controllers\creditsController;
use App\Http\Controllers\cardsController;

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

Auth::routes();

Route::group(['middleware' => 'auth:sanctum'], function () {
});

Route::get('/isAuthenticated', function () {
    if (Auth::check()) {
        return response()->json(['status' => true, 'role' => Auth::user()->role]);
    } else {
        return response()->json(['status' => false]);
    }
});

Route::resource('feedback', feedbackController::class)->except([
    'create', 'edit'
]);

Route::resource('newsletter', newsletterController::class)->except([
    'create', 'edit'
]);

Route::resource('deposit', depositsController::class)->except([
    'create', 'edit'
]);

Route::resource('credit', creditsController::class)->except([
    'create', 'edit'
]);

Route::resource('card', cardsController::class)->except([
    'create', 'edit'
]);
