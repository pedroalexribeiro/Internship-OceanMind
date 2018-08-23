<?php

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

Route::get('/', [
	'uses' => 'MainController@index',
]);

Route::get('/mpa/arrabida', [
	'uses' => 'MainController@arrabida',
]);
Route::get('/mpa/ria-formosa', [
	'uses' => 'MainController@ria_formosa',
]);
Route::get('/mpa/santo-andre-e-sancha', [
	'uses' => 'MainController@santo_andre_e_sancha',
]);
Route::get('/mpa/sudoeste-alentejano', [
	'uses' => 'MainController@sudoeste_alentejano',
]);