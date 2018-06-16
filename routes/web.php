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
Route::get('/threads/{channel?}', 'ThreadController@index')->name('threads.index');
Route::post('/threads', 'ThreadController@store')->name('threads.store');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.destroy');

// replies
Route::post('/threads/{thread}/replies', 'ReplyController@store')->name('replies.store');

Route::patch('/replies/{reply}', 'ReplyController@update')->name('replies.update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

// favorites
Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->name('favorites.store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->name('favorites.destroy');

// subscriptions (this is a named route prefix grouping)
Route::name('subscriptions.')->group(function () {
    Route::post('/threads/{thread}/subscriptions', 'ThreadSubscriptionController@store')->name('threads.store');
});

// profiles
Route::get('/profiles/{user}', 'ProfileController@show')->name('profiles.show');

Auth::routes();
