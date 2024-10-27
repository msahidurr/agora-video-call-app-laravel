<?php

use App\Http\Controllers\Agora\AgoraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'login');

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/agora-token', [AgoraController::class, 'generateToken']);
    Route::post('/get-token', [AgoraController::class, 'generateToken']);
    Route::get('/video-call-room', [AgoraController::class, 'videoCallRoom']);
    Route::get('/join-call', [AgoraController::class, 'joinCall']);
});

require __DIR__.'/auth.php';
