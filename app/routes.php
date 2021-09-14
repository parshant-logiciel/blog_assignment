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

Route::get('/', function()
{
    echo "welcome to blog assignment..";
    exit;
});


//Posts Data Mainupaltion Routes
Route::group(['before' => 'oauth'], function () {
    Route::get('/posts', 'PostController@index');
    Route::Post('post/store', 'PostController@store');
    Route::get('post/posts/{id}', 'PostController@show');
    Route::put('post/update/{id}', 'PostController@update');
    Route::delete('post/delete/{id}', 'PostController@destroy');

});


//Comments Routes
Route::group(['before' => 'oauth'],function () {
    Route::get('/comments', 'CommentController@index');
    Route::post('/comment/store', 'CommentController@store');
    Route::delete('/comment/delete/{id}', 'CommentController@destroy');
    Route::put('/comment/update/{id}', 'CommentController@update');
    Route::post('/comment/reply','CommentController@reply');
});


//User Routes
Route::post('/user/store','UserController@signup');
Route::post('/user/login','UserController@login');
Route::get('/user/index','UserController@index');
Route::group(['before' => 'oauth'],function () {
    Route::post('/user/active','UserController@active');
    Route::get('/user/logout','UserController@logout');
    Route::post('/user/uploadProfile', 'UserController@upload_profile');
    Route::get('/user/profile', 'UserController@profile');
    Route::post('/user/department', 'UserController@department');
    Route::get('/department/user','UserController@departmentIndex');
});

