<?php

use App\Http\Controllers\Analytic\AnalyticsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Automation\AutomationController;
use App\Http\Controllers\Automation\SmsChatController;
use App\Http\Controllers\Automation\WelcomePushController;
use App\Http\Controllers\Plan\PlanController;
use App\Http\Controllers\Publish\PublishAppController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use Osiset\ShopifyApp\Util;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    return view('layouts.app');
})->middleware(['auth.shopify'])->name('home');

Route::get('/content', function () {
    return view('layouts.app');
})->middleware(['auth.shopify'])->name('content');

Route::get('pricing', [PlanController::class, 'pricingView'])->name('pricing');

Route::group(['middleware' => ['auth.shopify']], function () {
    Route::get('get-plan', [PlanController::class, 'getPlan'])->name('get-plan');
    Route::get('get-plans', [PlanController::class, 'getPlans'])->name('get-plans');

    Route::post('publish-app', [PublishAppController::class, 'handle'])->name('publish-app');

    Route::post('dashboard', [DashboardController::class, 'index'])->name('dashboard');

//    Automation Routes
    Route::get('create-automation/{type}/{automationtype}/{id?}', [AutomationController::class, 'create'])->name('create-automations');
    Route::post('save-automation/{type}/{automationtype}/{id?}', [AutomationController::class, 'store'])->name('save-automations');
    Route::get('get-automations/{type}/{automationtype?}', [AutomationController::class, 'handle'])->name('get-automations');
    Route::get('delete-automation/{id}', [AutomationController::class, 'destroy'])->name('delete-automation');
    Route::post('test-automation-mail', [AutomationController::class, 'sendTestAutomationMail'])->name('test-automation-mail');
    Route::get('update-status/{id}', [AutomationController::class, 'updateStatus'])->name('update-status');

//  welcome push routes
    Route::get('get-welcome-push', [WelcomePushController::class, 'index'])->name('get-welcome-push');
    Route::post('save-welcome-push/{id}', [WelcomePushController::class, 'store'])->name('save-welcome-push');

    //change plan
    Route::get('mbilling/{id?}', [PlanController::class, 'appPlanChange'])->name('mbilling');

//    plan routes
    Route::get('get-recur-fund', [PlanController::class, 'getRecurFund'])->name('get-recur-fund');
    Route::post('enable-recur', [PlanController::class, 'enableRecur'])->name('enable-recur');

    Route::get('get-onetime-funds', [PlanController::class, 'getOnetimeFunds'])->name('get-onetime-funds');
    Route::get('get-sidebar', [PlanController::class, 'getSidebar'])->name('get-sidebar');
    Route::post('add-fund/onetime', [PlanController::class, 'addFundOneTime'])->name('add-fund-onetime');
    Route::post('add-fund/onetime', [PlanController::class, 'addFundOneTime'])->name('add-fund-onetime');


//    chat routes

    Route::get('sms/chat', [SmsChatController::class, 'index'])->name('get-chat');
    Route::post('sms/chat', [SmsChatController::class, 'sendSms'])->name('send-sms-chat');
//Analytics Routes
    Route::post('analytics', [AnalyticsController::class, 'index'])->name('analytics');

//    Settings Routes
    Route::get('settings', [SettingController::class, 'index'])->name('get-settings');
    Route::post('settings', [SettingController::class, 'store'])->name('save-settings');
});
Route::get('update-charge/{user_id}', [PlanController::class, 'updateCharge'])->name('update-charge');
// unsubscriber user

Route::get('unsubscribe/{type}/{customer_id}', [AutomationController::class, 'unsubscribeCustomer'])->name('unsubscribeCustomer');

/*
|--------------------------------------------------------------------------
| Authenticate: Auth
|--------------------------------------------------------------------------
|
| This route is hit when a shop comes to the app without a session token
| yet. A token will be grabbed from Shopify AppBridge Javascript
| and then forwarded back to the home route.
|
*/

//Route::get(
//    '/authenticate/oauth',
//    AuthController::class.'@oauth'
//)
//    ->middleware(['auth.shopify'])
//    ->name(Util::getShopifyConfig('route_names.authenticate.oauth'));

//test
Route::get('test', [TestController::class, 'index'])->name('test');

Route::get('flush', function (){
    request()->session()->flush();
});
