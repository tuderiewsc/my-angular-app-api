<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */
 
 /* Route::middleware('auth:api')->get('/user', 'UserController@auth_api'); */
 



Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::resource('articles', 'ArticleController');
Route::resource('categories', 'CategoryController');
Route::get('/articles/categories/{id}', 'CategoryController@articleList');
Route::get('/articlesList/{userid}', 'ArticleController@articlesList');
Route::get('/searchArticle/{phrase}', 'ArticleController@searchArticle');
Route::get('/headCats', 'CategoryController@headCats');
Route::post('uploadImg', 'uploadcontroller@uploadImg');
Route::post('bulk_articde_destroy', 'ArticleController@bulk_destroy');
Route::get('uploadImagesList/{section}', 'uploadcontroller@uploadImagesList');
Route::put('update_status/{id}', 'ArticleController@update_status');
Route::put('update_user/{id}', 'UserController@update_user');
Route::get('get_user/{id}', 'UserController@get_user');








