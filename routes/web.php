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

Route::get('/', function () {
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

    // Socialite login routes.
    Route::get('login/{provider}', 'SocialiteController@login')
        ->where('provider', 'google|facebook')->name('socialite.login');
    Route::get('login/{provider}/callback', 'SocialiteController@handleCallback')
        ->where('provider', 'google|facebook');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', 'WalletController@index')->name('main');
    Route::get('new-wallet', 'WalletController@showCreateWallet')->name('create.wallet');
    Route::post('wallet', 'WalletController@store')->name('store.wallet');
    Route::get('wallet/{wallet_id}', 'WalletController@show')->name('show.wallet');
    Route::get('wallet/{wallet_id}/update', 'WalletController@showUpdate')->name('show.update.wallet');
    Route::post('wallet/{wallet_id}/update', 'WalletController@update')->name('update.wallet');
    Route::get('wallet/{wallet_id}/delete', 'WalletController@destroy')->name('delete.wallet');
    Route::get('invoice/{invoice_id}/delete', 'WalletController@destroyInvoice')->name('delete.invoice');
    Route::get('wallet/{wallet_id}/new-invoice', 'WalletController@showCreateInvoice')->name('create.invoice');
    Route::post('wallet/{wallet_id}/new-invoice', 'WalletController@updateBalance')->name('store.invoice');
});

