<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/bearer_authentication', 'UserController@bearer_authentication');
Route::get('/user/basic_authentication', 'UserController@basic_authentication');

Route::middleware([CheckToken::class])->group(function () {
    Route::post('/task/create', 'TaskController@create');
    Route::get('/task/read', 'TaskController@read');
    Route::put('/task/update', 'TaskController@update');
    Route::delete('/task/delete', 'TaskController@delete');

    Route::post('/user/create', 'UserController@create');
    Route::put('/user/update/room_id', 'UserController@updateRoomId');
    Route::delete('/user/delete', 'UserController@delete');

    Route::post('/work/create', 'WorkController@create');
    Route::get('/work/read', 'WorkController@read');
    Route::delete('/work/delete', 'WorkController@delete');

    Route::get('/question/read', 'QuestionController@read');
    Route::post('/question/create', 'QuestionController@create');
    Route::delete('/question/delete', 'QuestionController@delete');

    Route::get('/answer/read', 'AnswerController@read');
    Route::post('/answer/create', 'AnswerController@create');
    Route::delete('/answer/delete', 'AnswerController@delete');

    // LINE メッセージ送信用
    Route::post('/line/message', 'LineMessengerController@message');
    Route::post('/line/today_worker', 'LineMessengerController@today_worker');
});
