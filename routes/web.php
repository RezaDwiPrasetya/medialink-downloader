<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DownloadController::class, 'index'])->name('home');
Route::post('/check', [DownloadController::class, 'check'])->name('check');
