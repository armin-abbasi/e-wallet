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

Route::get('/', function() {
    return redirect()->route('main');
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::get('login', 'UserController@login')->name('login');
    Route::post('logout', 'LoginController@logout ')->name('logout');
    Route::post('sign-in', 'UserController@signIn')->name('sign-in');
    Route::get('register', 'UserController@register')->name('register');
    Route::post('sign-up', 'UserController@signUp')->name('sign-up');
    Route::post('resend', 'VerificationController@resend')->name('verification.resend');
    Route::get('verify', 'VerificationController@show')->name('verification.notice');
    Route::get('verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', 'DashboardController@index')->name('main');
});

//Auth::routes(['verify' => true]);
