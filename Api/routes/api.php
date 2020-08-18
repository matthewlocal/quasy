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

//Public
Route::get('/',                 'ApiController@version');

//Registration
Route::post('/register',        'ApiController@register')->middleware('log:api');
Route::post('/login',           'ApiController@login')->middleware('log:api');

//Users Area
Route::post('/user/list',       'ApiController@getUserList')->middleware('auth:api', 'log:api');
