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

use Illuminate\Http\Request;

Route::get('/', function () {
    return '精小弘';
});

Route::any('/wechat/serve', 'Wechat\ServerController@server');

// wechat oauth
Route::post('wechat/login', 'Auth\LoginController@login');
Route::get('redirect', 'OauthController@redirect');
Route::get('oauth', 'OauthController@oauth');
Route::get('wechat/openid', 'Auth\LoginController@wechat');
Route::post('user/agree', 'Auth\LoginController@agreeSend');
Route::get('user/change', 'Auth\LoginController@changeBind');
Route::get('user/send/cancel', 'Auth\LoginController@cancel');

//message
Route::get('/message/show/{id}', 'MessageController@show');


//开发部招新通知
Route::get('/ms/sure', 'JhController@sureGoBs')->middleware('bind.check');
Route::get('/ms/redirect', 'JhController@wxRedirect');
Route::get('/ms/sure/num', 'JhController@getSureNum');


Route::get('success', function () {
   return view('jxh.success');
});


//admin
Route::get('/admin', function (){
    return response()->view('admin.index');
});
Route::post('/admin/login', 'AdminController@login');
Route::group(['middleware' => ['admin.check', 'api.auth']], function() {
    Route::post('/message/create', 'MessageController@create');
    Route::post('/message/update', 'MessageController@update');
    Route::get('/message/{id}', 'MessageController@getMessage');
    Route::get('/messages', 'MessageController@getMessages');
    Route::get('/message/pre/{id}', 'MessageController@pre');
    Route::post('/message/send/{id}', 'MessageController@sendAll');

    //开发部招新通知
    Route::post('/ms/result', 'JhController@sendMsResult');
});
Route::get('/user/bind/count', 'AdminController@countBind');


Route::get('/message/agree/{hashid}', 'AdminController@agree');
Route::get('/message/agree/cancel/{hashid}', 'AdminController@cancel');
Route::get('/message/admin/show/{hashid}', 'AdminController@show');
Route::get('/message/send/action/info', function () {
   return view('jxh.success', ['content' => '操作成功']);
});

Route::post('/message/upload', 'MessageController@upload');


//user
//Route::get('/user/cancel/send', 'OauthController');


// 工大毕业照
Route::get('jxh/byz/oauth', 'GdByzController@oauth');
Route::get('jxh/byz', 'GdByzController@index');
Route::post('jxh/byz/upload', 'GdByzController@upload');
Route::post('jxh/byz/submit', 'GdByzController@submit');
Route::get('jxh/byz/show/{hashid}', 'GdByzController@show');
Route::get('jxh/byz/await', 'GdByzController@await');
Route::get('jxh/test', 'GdByzController@getZjz');





//oauth api
Route::post('/stdcode/to/sid', 'OauthController@stuCodeToSid');
Route::get('/oauth/classmate', 'OauthController@classmateOauth');
Route::get('/classmate', 'OauthController@toClassmateDetail');
