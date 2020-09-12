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

Route::get('/', 'PageController@landingPage')->name('landing_page');
Route::get('/home', 'HomeController@index')->name('home');

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
                });

                Route::prefix('analysis')->name('analysis.')->group(function () {
                    Route::get('/', 'TopicController@getAnalysis')->name('index');
                    Route::post('/', 'TopicController@postAnalysis')->name('store');
                    Route::post('/complement-graph', 'TopicController@postComplementGraph')->name('complement_graph');
                    Route::post('/nro-graph', 'TopicController@postComplementGraph')->name('complement_graph');
                });

                Route::prefix('graph')->name('graph.')->group(function () {
                    Route::get('/', 'TopicGraphController@index')->name('index');
                    // Route::post('/generate', 'TopicGraphController@generate')->name('generate');
                    Route::post('/normalize', 'TopicGraphController@normalize')->name('normalize');
                    Route::post('/analyze', 'TopicGraphController@analyze')->name('analyze');
                });

                Route::prefix('selected')->name('selected.')->group(function () {
                    // Route::get('/', 'TopicController@getSelectedTopic')->name('index');
                    Route::post('/', 'TopicController@setSelectedTopic')->name('store');
                });
            });
        });
}

// Route::prefix('public')->name('public.')->middleware(['auth'])->group(function () {
//     Route::prefix('topics')->name('topics.')->group(function () {
//         Route::get('/', 'TopicController@index')->name('index');
//         Route::post('/', 'TopicController@store')->name('store');
//         Route::get('/create', 'TopicController@create')->name('create');

//         Route::prefix('{topic}')->group(function () {
//             Route::post('/mining', 'TopicController@mining')->name('mining');

//             Route::prefix('tweets')->name('tweets.')->group(function () {
//                 Route::get('/', 'PublicTopicTweetController@index')->name('index');
//             });

//             Route::prefix('analysis')->name('analysis.')->group(function () {
//                 Route::get('/', 'TopicController@getAnalysis')->name('index');
//                 Route::post('/', 'TopicController@postAnalysis')->name('store');
//                 Route::post('/complement-graph', 'TopicController@postComplementGraph')->name('complement_graph');
//             });

//             Route::prefix('selected')->name('selected.')->group(function () {
//                 // Route::get('/', 'TopicController@getSelectedTopic')->name('index');
//                 Route::post('/', 'TopicController@setSelectedTopic')->name('store');
//             });
//         });
//     });
// });

// // TODO: MAKE THE ROUTES DRY FOR PUBLIC AND USER BY USING ONE CONTROLLER !!!!

// Route::prefix('topics')->name('topics.')->middleware(['auth'])->group(function () {
//     Route::get('/', 'TopicController@index')->name('index');
//     Route::post('/', 'TopicController@store')->name('store');
//     Route::get('/create', 'TopicController@create')->name('create');

//     Route::prefix('{topic}')->group(function () {
//         Route::post('/mining', 'TopicController@mining')->name('mining');

//         Route::prefix('tweets')->name('tweets.')->group(function () {
//             Route::get('/', 'TopicTweetController@index')->name('index');
//         });
//     });
// });
