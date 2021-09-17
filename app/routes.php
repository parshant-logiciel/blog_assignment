<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function () {
    echo "welcome to blog assignment..";
    exit;
});


//Posts Data Mainupaltion Routes
Route::group(['before' => 'oauth'], function () {
    Route::get('/posts', 'PostController@index');
    Route::Post('post/store', 'PostController@store');
    Route::get('post/{id}', 'PostController@show');
    Route::put('post/{id}', 'PostController@update');
    Route::delete('post/{id}', 'PostController@destroy');
    //to add a Favorite Post
    Route::Put('post/addFavorite', 'PostController@addFavorite');
});

//Comments Routes
Route::group(['before' => 'oauth'], function () {
    Route::get('/comments', 'CommentController@index');
    Route::post('/comment/store', 'CommentController@store');
    Route::put('/comment/{id}', 'CommentController@update');
    Route::delete('/comment/{id}', 'CommentController@destroy');
    Route::post('/comment/reply', 'CommentController@reply');
});


//User Routes
Route::post('/user/store', 'UserController@signup');
Route::post('/user/login', 'UserController@login');
Route::get('/user/index', 'UserController@index');
Route::group(['before' => 'oauth'], function () {
    Route::post('/user/active', 'UserController@active');
    Route::post('/user/uploadProfile', 'UserController@upload_profile');
    Route::get('/user/profile', 'UserController@profile');
    Route::get('/user/logout', 'UserController@logout');
    Route::post('/user/department', 'UserController@department');
    Route::get('/department/user', 'UserController@departmentIndex');
});
