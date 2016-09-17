<?php
// use herysepty\Http\Controllers\AppController; 

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
Route::get('politik/{file_name}', 'AppController@clusterPolitik');

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

});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'TweetController@h');
    Route::get('/home', 'TweetController@h');
    Route::get('/help', function(){
        return view('contents.help');
    });
    // 
    Route::get('/denied', function(){
        return view('errors.404');
    });
    Route::get('/denied/error/{id}', function(){
        return view('errors.200');
    });
    //tweets
    Route::get('/tweet/view', 'TweetController@index');
    Route::get('/tweet/clear/view', 'TweetController@viewClearTweet');
    Route::get('/tweet/get', function(){
        return view('contents.get_tweets');
    });
    #Clustering
    Route::post('/tweet/store', 'TweetController@getTweet');
    Route::get('/clustering',function(){
        return view('contents.form_clustering');
    });
    Route::post('/clustering','TweetController@clustering');
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
});