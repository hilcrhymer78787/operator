<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;

// コントローラーをuseで読み込み
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LineMessengerController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 認証不要ルート
Route::get('/user/bearer_authentication', [UserController::class, 'bearer_authentication']);
Route::get('/user/basic_authentication', [UserController::class, 'basic_authentication']);

// ミドルウェアCheckTokenが必要なグループ
Route::middleware([CheckToken::class])->group(function () {
    Route::post('/task/create', [TaskController::class, 'create']);
    Route::get('/task/read', [TaskController::class, 'read']);
    Route::put('/task/update', [TaskController::class, 'update']);
    Route::delete('/task/delete', [TaskController::class, 'delete']);

    Route::post('/user/create', [UserController::class, 'create']);
    Route::put('/user/update/room_id', [UserController::class, 'updateRoomId']);
    Route::delete('/user/delete', [UserController::class, 'delete']);

    Route::post('/work/create', [WorkController::class, 'create']);
    Route::post('/work/all', [WorkController::class, 'all']);
    Route::get('/work/read', [WorkController::class, 'read']);
    Route::delete('/work/delete', [WorkController::class, 'delete']);

    Route::get('/question/read', [QuestionController::class, 'read']);
    Route::post('/question/create', [QuestionController::class, 'create']);
    Route::delete('/question/delete', [QuestionController::class, 'delete']);

    Route::get('/answer/read', [AnswerController::class, 'read']);
    Route::post('/answer/create', [AnswerController::class, 'create']);
    Route::delete('/answer/delete', [AnswerController::class, 'delete']);

    Route::get('/report/read', [ReportController::class, 'read']);
    Route::post('/report/create', [ReportController::class, 'create']);
    Route::put('/report/edit', [ReportController::class, 'edit']);
    Route::delete('/report/delete', [ReportController::class, 'delete']);

    Route::get('/shift/read', [ShiftController::class, 'read']);
    Route::post('/shift/create', [ShiftController::class, 'create']);

    Route::get('/quiz/read', [QuizController::class, 'read']);
    Route::post('/quiz/create', [QuizController::class, 'create']);
    Route::delete('/quiz/delete', [QuizController::class, 'delete']);

    // LINE メッセージ送信用
    Route::post('/line/message', [LineMessengerController::class, 'message']);
    Route::post('/line/today_worker', [LineMessengerController::class, 'today_worker']);
});
