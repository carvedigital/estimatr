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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [
        'as' => 'home',
        function () {
            return view('welcome');
        },
    ]);

    Route::get('authenticate/login', ['as' => 'login', 'uses' => 'Auth\AuthController@login']);
    Route::get('authenticate/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);
    Route::get('authenticate/complete', ['as' => 'login-complete', 'uses' => 'Auth\AuthController@loginComplete']);

    Route::group(['middleware' => ['auth']], function () {
        Route::get('account', ['as' => 'account', 'uses' => 'Users\AccountController@getAccount']);
        Route::get('estimates', ['as' => 'estimates', 'uses' => 'Estimates\CreateController@getList']);
        Route::get('estimates/create', ['as' => 'new-estimate', 'uses' => 'Estimates\CreateController@getCreate']);
        Route::post('estimates/create', ['as' => 'do-create', 'uses' => 'Estimates\CreateController@postCreate']);
        Route::get('estimates/{estimate}',
            ['as' => 'get-estimate', 'uses' => 'Estimates\EditorController@getEstimate']);
    });
});
Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::delete('estimates/{estimate}',
            ['as' => 'api-delete-estimate', 'uses' => 'Api\EstimatesController@deleteEstimate']);
        Route::put('estimates/{estimate}', ['as' => 'api-update-estimate', 'uses' => 'Api\EstimatesController@updateEstimate']);
        Route::get('estimates/{estimate}', ['as' => 'api-get-estimate', 'uses' => 'Api\EstimatesController@getEstimate']);
        Route::get('user', ['as' => 'api-get-user', 'uses' => 'Api\UsersController@getUser']);
        Route::put('user', ['as' => 'api-put-user', 'uses' => 'Api\UsersController@updateUser']);
    });
});
