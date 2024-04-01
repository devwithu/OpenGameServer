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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::apiResource('keys', 'App\Http\Controllers\KeyController');
Route::apiResource('coupons', 'App\Http\Controllers\CouponController');
Route::post('coupons/use_coupon', 'App\Http\Controllers\CouponController@useCoupon');


Route::get('lb/create', 'App\Http\Controllers\LeaderboardController@create');
Route::get('lb/remove/{priv_key}', 'App\Http\Controllers\LeaderboardController@remove');

Route::get('lb/set/{priv_key}/{user_id}/{score}', 'App\Http\Controllers\LeaderboardController@update');
Route::get('lb/del/{priv_key}/{user_id}', 'App\Http\Controllers\LeaderboardController@destroy');
Route::get('lb/clear/{priv_key}', 'App\Http\Controllers\LeaderboardController@clear');

Route::get('lb/gets/{priv_key}/{start}/{end}', 'App\Http\Controllers\LeaderboardController@index');
Route::get('lb/get/{priv_key}/{user_id}', 'App\Http\Controllers\LeaderboardController@show');

