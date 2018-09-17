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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::prefix('inbox')->middleware(['auth'])->group(function () {
    Route::get('', 'InboxController@index');
    Route::get('new', 'InboxController@create');
    Route::post('new', 'InboxController@store');
    Route::get('{id}', 'InboxController@show');
    Route::post('{id}', 'InboxController@add');
    Route::delete('{id}', 'InboxController@destroy');
});

