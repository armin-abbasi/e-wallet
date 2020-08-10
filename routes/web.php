<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'UserController@login')->name('login');
    Route::post('sign-in', 'UserController@signIn')->name('sign-in');
    Route::get('register', 'UserController@register')->name('register');
    Route::post('sign-up', 'UserController@signUp')->name('sign-up');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'UserController@index');
});
