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

Route::prefix('/todoList')->group(function() {
    Route::get('/', 'todoListController@index')->name('index');
    Route::post('/add', 'todoListController@create')->name('add');
    Route::delete('/delete', 'todoListController@delete')->name('delete');
    Route::post('/edit', 'todoListController@getEdit')->name('getEdit');
    Route::put('/save', 'todoListController@save')->name('save');
});