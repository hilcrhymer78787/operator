<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineMessengerController;
use App\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});

// LINE メッセージ受信（Webhook）
Route::post('/line/webhook', [LineMessengerController::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
