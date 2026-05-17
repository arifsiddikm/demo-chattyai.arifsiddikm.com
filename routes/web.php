<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Chat routes (authenticated)
Route::middleware('auth')->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/{session}', [ChatController::class, 'show'])->name('show');
    Route::post('/new', [ChatController::class, 'newSession'])->name('new');
    Route::delete('/{session}', [ChatController::class, 'deleteSession'])->name('delete');
    Route::put('/{session}/rename', [ChatController::class, 'renameSession'])->name('rename');
    Route::post('/{session}/stream', [ChatController::class, 'stream'])->name('stream');
});
