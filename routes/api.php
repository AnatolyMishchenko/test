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

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['middleware' => 'isAdmin'], function () {
        Route::get('users', 'UserController@index');
        Route::get('users/edit/{id}', 'UserController@edit');
        Route::post('users/update/{id}', 'UserController@update');
        Route::get('post/edit/{id}', 'HistoryController@edit');
        Route::post('post/update/{id}', 'HistoryController@update');
        Route::get('post', 'HistoryController@index');
    });
    Route::group(['middleware' => 'isAdminOrSelf'], function () {
        Route::get('users/{id}', 'UserController@show');
    });
    Route::get('options', 'UserController@option');
    Route::get('transactions', 'UserController@transactions');
    Route::post('givepw', 'ParrotWingsController@givePW');
});
