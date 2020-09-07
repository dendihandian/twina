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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('public')->name('public.')->middleware(['auth'])->group(function () {
    Route::prefix('topics')->name('topics.')->group(function () {
        Route::get('/', 'PublicTopicController@index')->name('index');
        Route::post('/', 'PublicTopicController@store')->name('store');
        Route::get('/create', 'PublicTopicController@create')->name('create');

        Route::prefix('{topic}')->group(function () {
            Route::post('/mining', 'PublicTopicController@mining')->name('mining');

            Route::prefix('tweets')->name('tweets.')->group(function () {
                Route::get('/', 'PublicTopicTweetController@index')->name('index');
            });

            Route::prefix('analysis')->name('analysis.')->group(function () {
                Route::get('/', 'PublicTopicController@getAnalysis')->name('index');
                Route::post('/', 'PublicTopicController@postAnalysis')->name('store');
            });
        });
    });
});

Route::prefix('topics')->name('topics.')->middleware(['auth'])->group(function () {
    Route::get('/', 'TopicController@index')->name('index');
    Route::post('/', 'TopicController@store')->name('store');
    Route::get('/create', 'TopicController@create')->name('create');

    Route::prefix('{topic}')->group(function () {
        Route::post('/mining', 'TopicController@mining')->name('mining');

        Route::prefix('tweets')->name('tweets.')->group(function () {
            Route::get('/', 'TopicTweetController@index')->name('index');
        });
    });
});
