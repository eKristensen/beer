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

Route::get('/products', 'ProductController@index')->middleware('auth');
Route::post('/products', 'ProductController@store')->middleware('auth');
Route::patch('/products', 'ProductController@patch')->middleware('auth');

Route::get('/rooms', 'RoomController@index');
Route::get('/rooms/edit', 'RoomController@edit')->middleware('auth')->name('home');
Route::post('/rooms', 'RoomController@store')->middleware('auth');
Route::patch('/rooms', 'RoomController@patch')->middleware('auth');
Route::get('/rooms/{room}', 'RoomController@show')->middleware('auth');

Route::get('/deposit', 'DepositController@index')->middleware('auth');
Route::post('/deposit', 'DepositController@store')->middleware('auth');

Route::post('/summary', 'SummaryController@store')->middleware('auth');
Route::patch('/summary', 'SummaryController@patch')->middleware('auth');

Route::get('/refund', 'BeerController@refund');

Route::get('/highscore', 'HighScoreController@index');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
