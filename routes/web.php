<?php

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

Route::get('/', function () {
    return '精小弘';
});

Route::any('/wechat/serve', 'Wechat\ServerController@server');

// wechat oauth
Route::get('wechat/login', 'Auth\LoginController@login');
Route::get('redirect', 'OauthController@redirect');
Route::get('oauth', 'OauthController@oauth');
Route::get('wechat/openid', 'Auth\LoginController@wechat');
//

//message
Route::get('/message/show/{id}', 'MessageController@show');
//

Route::get('/test', 'Auth\LoginController@agreeSend');


