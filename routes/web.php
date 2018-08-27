<?php

use Vinkla\Hashids\Facades\Hashids;

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

// find a thread from it's decoded hashed id
Route::bind('thread', function ($value, $route) {
    $id = Hashids::connection('threads')->decode($value)[0];

    return \App\Thread::findOrFail($id);
});


Route::get('/', function () {
    return redirect()->route('threads.index');
})->name('home');


// subscriptions (this is above threads due to wildcard naming conflict with threads.destroy)
Route::name('subscriptions.')->group(function () {
    Route::post('/threads/{thread}/subscriptions', 'ThreadSubscriptionController@store')->name('threads.store');

    Route::delete('/threads/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->name('threads.destroy');
});

// threads
Route::get('/threads/create', 'ThreadController@create')->name('threads.create');
Route::get('/threads/{channel?}', 'ThreadController@index')->name('threads.index');
Route::post('/threads', 'ThreadController@store')->name('threads.store');
Route::post('threads/{thread}/lock', 'LockThreadController@store')->name('threads.lock.store'); // lock a thread
Route::delete('threads/{thread}/lock', 'LockThreadController@destroy')->name('threads.lock.destroy'); // unlock a thread
Route::get('/threads/{channel}/{thread}/{slug}', 'ThreadController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}/{slug}', 'ThreadController@destroy')->name('threads.destroy');  // delete a thread
Route::patch('/threads/{channel}/{thread}/{slug}', 'ThreadController@update')->name('threads.update');  // update a thread

// Channels
Route::get('/channels/create', 'ChannelController@create')->name('channels.create');
Route::post('/channels', 'ChannelController@store')->name('channels.store');
Route::get('/channels/confirmation/create', 'ChannelConfirmationController@create')->name('channels.confirm.create');
Route::post('/channels/confirmation', 'ChannelConfirmationController@store')->name('channels.confirm.store');
Route::delete('/channels/confirmation', 'ChannelConfirmationController@destroy')->name('channels.confirm.destroy');

// replies
Route::post('/threads/{thread}/replies', 'ReplyController@store')->name('replies.store');
Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('replies.best.store');
Route::patch('/replies/{reply}', 'ReplyController@update')->name('replies.update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

// favorites
Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->name('favorites.store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->name('favorites.destroy');

// users
Route::get('/profiles/{user}', 'ProfileController@show')->name('profiles.show');

Route::get('/profiles/{user}/settings/edit', 'ProfileController@edit')->name('profiles.settings.edit');

Route::patch('/profiles/{user}/settings', 'ProfileController@update')->name('profiles.settings.update');

// search
Route::prefix('search')->group(function () {
    Route::get('/threads', 'Search\ThreadSearchController@index')->name('search.threads');
});

// api endpoints
Route::prefix('api')->group(function () {
    Route::namespace('Api')->group(function () {
        //User lookup for search
        Route::get('/profiles/users', 'Users\UserController@index')->name('api.users.index');

        // Uploads routes
        Route::post('/uploads/images/{key}/{user}', 'Uploads\ImageController@store')
            ->name('api.uploads.images.store');

        // User Notifications
        Route::get('/profiles/{user}/notifications', 'Users\NotificationController@index')
            ->name('users.notifications.index');

        Route::patch('/profiles/{user}/notifications/{notification?}', 'Users\NotificationController@update')
            ->name('users.notifications.update');
    });
});

// Auth::routes();
// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');
$this->get('/register/confirmation', 'Auth\RegisterController@confirm')->name('register.confirm');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');
