<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/twitter/insert', 'AppController@twitter');
// Route::get('/', [
//     'as' => 'tweet', 'uses' => 'TweetController@index'
// ]);


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('/user/{id}/edit', 'UserController@edit');
    Route::post('/user/update', 'UserController@update');
});
Route::group(['middleware' => ['web','admin']], function () {
    // users
    Route::get('/user/view', 'UserController@index');
    Route::get('/user/add', 'UserController@form');
    Route::post('/user/store', 'UserController@store');
    Route::get('/user/{id}/delete', 'UserController@destroy');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', function(){
        // return view('home');
        return redirect('/tweet/view');
    });
    Route::get('/help', function(){
        return view('contents.help');
    });
    // 
    Route::get('/denied', function(){
        return view('errors.404');
    });
    //tweets
    Route::get('/tweet/view', 'TweetController@index');
    Route::get('/tweet/clear/view', 'TweetController@viewClearTweet');
    Route::get('/tweet/get', function(){
        return view('contents.get_tweets');
    });
    #Clasification
    Route::post('/tweet/store', 'TweetController@getTweet');
    Route::get('/classification','TweetController@formClassification');
    Route::post('/classification','TweetController@classification');
    Route::get('/processing', function(){
        return view('contents.form_processing');
    });
    Route::post('/processing','TweetController@processingTweets');
    // Stopwords
    Route::get('/stopword/view', 'StopwordController@index');
    Route::post('/stopword/store', 'StopwordController@store');
    Route::get('/stopword/edit/{id}', 'StopwordController@edit');
    Route::post('/stopword/update', 'StopwordController@update');
    Route::get('/stopword/delete/{id}', 'StopwordController@destroy');
    Route::get('/stopword/search','StopwordController@search');
    
    
    // 
    Route::get('/test', 'AppController@test');
    Route::get('/count-clear-tweet', 'AppController@countClearTweets');
    Route::get('/algorithm', function(){
        return view('contents.list_algorithm');
    });
    // chat
    Route::post('/chat/add', 'AppController@chat_add');
    Route::get('/chat/show', 'AppController@chat_show');  
});