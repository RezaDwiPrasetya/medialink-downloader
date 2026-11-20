<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DownloadController::class, 'index'])->name('home');
Route::post('/check', [DownloadController::class, 'check'])->name('check');
Route::post('/download', [DownloadController::class, 'download'])->name('download');

Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::get('/downloads/{id}/serve', [HistoryController::class, 'serve'])->name('downloads.serve');

