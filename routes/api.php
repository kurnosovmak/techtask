<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::resource('v1/product','ProductController')->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
Route::resource('v1/category','CategoryController')->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
Route::get('v1/search','SearchProductController@search');
