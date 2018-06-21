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
    return redirect()->route('threads.index');
});


// subscriptions (this is above threads due to wildcard naming conflict with threads.destroy)
Route::name('subscriptions.')->group(function () {
    Route::post('/threads/{thread}/subscriptions', 'ThreadSubscriptionController@store')->name('threads.store');
    Route::delete('/threads/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->name('threads.destroy');
});

// threads
Route::get('/threads/create', 'ThreadController@create')->name('threads.create');
Route::get('/threads/{channel?}', 'ThreadController@index')->name('threads.index');
Route::post('/threads', 'ThreadController@store')->name('threads.store');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.destroy');  //change route

// replies
Route::post('/threads/{thread}/replies', 'ReplyController@store')->name('replies.store');

Route::patch('/replies/{reply}', 'ReplyController@update')->name('replies.update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

// favorites
Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->name('favorites.store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->name('favorites.destroy');

// users
Route::get('/profiles/{user}', 'ProfileController@show')->name('profiles.show');

// api endpoints
Route::prefix('api')->group(function () {

    // User Notifications
    Route::get('/profiles/{user}/notifications', 'UserNotificationController@index')
        ->name('users.notifications.index');

    Route::patch('/profiles/{user}/notifications/{notification?}', 'UserNotificationController@update')
        ->name('users.notifications.update');
});

Auth::routes();
