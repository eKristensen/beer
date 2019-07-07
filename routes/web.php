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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', 'ProductController@index')->middleware('auth');
Route::post('/products', 'ProductController@store')->middleware('auth');
Route::patch('/products', 'ProductController@patch')->middleware('auth');

Route::get('/rooms', 'RoomController@index');
Route::get('/rooms/edit', 'RoomController@edit')->middleware('auth');
Route::post('/rooms', 'RoomController@store')->middleware('auth');
Route::patch('/rooms', 'RoomController@patch')->middleware('auth');
Route::get('/rooms/{room}', 'RoomController@show')->middleware('auth');

Route::get('/deposit', 'RoomController@depositShow')->middleware('auth');
Route::post('/deposit', 'RoomController@depositStore')->middleware('auth');

Route::get('/refund', 'BeerController@refund');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
