<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChatController;

Route::get('/', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

Route::get('/api/vultr-key', function () {
    return response()->json(['vultr_key' => env('VULTR_SERVERLESS_URL')]);
});

Route::get('/', function () {
    return view('welcome');
});
