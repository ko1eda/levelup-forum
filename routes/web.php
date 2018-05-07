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


// Threads Routes
Route::get('/threads/create', 'ThreadController@create')->name('threads.create'); //possible /{channel}/create

// Note: ? means that the parameter is optional
Route::get('/threads/{channel?}', 'ThreadController@index')->name('threads.index');

Route::post('/threads', 'ThreadController@store')->name('threads.store');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('threads.reply');

Auth::routes();
