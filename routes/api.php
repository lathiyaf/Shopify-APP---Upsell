<?php

use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Automation\WelcomePushController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('get-plans', [PlanController::class, 'getPlans'])->name('get-plans');
Route::post('store-images', [WelcomePushController::class, 'storeImages'])->name('storeimages'); // to add image of logo value

Route::group(['middleware' => ['cors']], function () {
    Route::get('welcome-push', [PlanController::class, 'getWelcomePush'])->name('welcome-push');
});
