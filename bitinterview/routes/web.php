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


Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('home');


//// Dashboard ////
Route::get('/dashboard/edituser', 'DashboardController@editUser');
Route::post('/dashboard/updateuser', 'DashboardController@updateUser');

Route::get('/dashboard/createbuy/{fiat_name?}/{crypto_name?}', 'DashboardController@createbuy');
Route::post('/dashboard/createbuy', 'DashboardController@insertbuy');

Route::get('/dashboard/createsell/{fiat_name?}/{crypto_name?}', 'DashboardController@createsell');
Route::post('/dashboard/createsell', 'DashboardController@insertsell');

Route::get('/dashboard/deposit/{id?}', 'DashboardController@showdeposit');
Route::post('/dashboard/deposit/', 'DashboardController@deposit');

Route::get('/dashboard/transfer/{crypto_name?}', 'DashboardController@showtransfer');
Route::post('/dashboard/transfer', 'DashboardController@transfer');

///// Trade /////
Route::get('/trade/{id}/{fiat_name}/{crypto_name}', 'TradeController@index');
Route::post('/trade/{id}', 'TradeController@trade');

/// index /////
Route::get('/{action?}/{fiat?}/{crypto?}', 'IndexController@show');

