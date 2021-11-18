<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/bearer_authentication', 'UserController@bearer_authentication');
Route::get('/user/basic_authentication', 'UserController@basic_authentication');

Route::middleware([CheckToken::class])->group(function () {
// 完成
Route::post('/task/create', 'TaskController@create');
Route::get('/task/read', 'TaskController@read');
Route::put('/task/update', 'TaskController@update');
Route::delete('/task/delete', 'TaskController@delete');

// 完成
Route::post('/user/create', 'UserController@create');
Route::put('/user/update/room_id', 'UserController@updateRoomId');
Route::delete('/user/delete', 'UserController@delete');

// 完成
Route::post('/work/create', 'WorkController@create');
Route::get('/work/read', 'WorkController@read');
Route::delete('/work/delete', 'WorkController@delete');

Route::get('/question/read', 'QuestionController@read');
Route::post('/question/create', 'QuestionController@create');
Route::delete('/question/delete', 'QuestionController@delete');

Route::get('/answer/read', 'AnswerController@read');
Route::post('/answer/create', 'AnswerController@create');
Route::delete('/answer/delete', 'AnswerController@delete');
});
