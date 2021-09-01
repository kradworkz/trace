<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::any('login_from_dost6/{username}/{password}', 'ApiController@handleLoginForDost6');
Route::any('get_all_users_for_dost6', 'ApiController@getAllUsersForDost6');
Route::any('get_all_active_users_for_dost6', 'ApiController@getAllActiveUsersForDost6');

