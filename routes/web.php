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

Auth::routes();

Route::prefix('register')->middleware('prodRestrict')->group(function () {
});

// pages
Route::get('/', 'PageController@landingPage')->name('landing_page');
Route::get('/highlighted', 'PageController@highlightedTopic')->name('highlighted');
Route::get('/public-topics', 'PageController@publicTopics')->name('public_topics');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile/reset-password', 'HomeController@resetPassword')->name('profile.reset_password');

$startingPoints = [
    [
        'prefix' => 'public/topics',
        'name' => 'public.topics.',
        'middleware' => ['isPub'],
    ],
    [
        'prefix' => 'topics',
        'name' => 'topics.',
        'middleware' => [],
    ],
];

foreach ($startingPoints as $startingPoint) {
    Route::prefix($startingPoint['prefix'])
        ->name($startingPoint['name'])
        ->middleware(array_merge(['auth'], $startingPoint['middleware']))
        ->group(function () {
            Route::get('/', 'TopicController@index')->name('index');
            Route::post('/', 'TopicController@store')->name('store');
            Route::get('/create', 'TopicController@create')->name('create');

            Route::prefix('{topic}')->group(function () {
                Route::post('/delete', 'TopicController@delete')->name('delete');

                Route::post('/mining', 'TopicController@mining')->name('mining');

                Route::prefix('tweets')->name('tweets.')->group(function () {
                    Route::get('/', 'TopicTweetController@index')->name('index');
                    Route::post('/mine', 'TopicTweetController@mine')->name('mine');
                    Route::post('/analyze', 'TopicTweetController@analyze')->name('analyze');
                });

                Route::prefix('graph')->name('graph.')->group(function () {
                    Route::get('/', 'TopicGraphController@index')->name('index');
                    Route::post('/generate', 'TopicGraphController@generate')->name('generate');
                    Route::post('/normalize', 'TopicGraphController@normalize')->name('normalize');
                    Route::post('/analyze', 'TopicGraphController@analyze')->name('analyze');
                });

                Route::prefix('selected')->name('selected.')->group(function () {
                    Route::post('/', 'TopicController@setSelected')->name('store');
                });
            });
        });
}
