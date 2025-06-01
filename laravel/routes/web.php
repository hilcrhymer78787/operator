<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// LINE メッセージ受信
Route::post('/line/webhook', 'LineMessengerController@webhook');