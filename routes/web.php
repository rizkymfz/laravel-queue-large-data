<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', [UploadController::class, 'index']);
Route::get('/progress', [UploadController::class, 'progress']);
Route::get('/progress/data', [UploadController::class, 'progressForCsvStoreProcess'])->name('csvStoreProcess');
Route::get('/progress/call-batch-progress', [UploadController::class, 'callBatchProgress'])->name('callBatchProgress');

Route::post('/upload/file', [UploadController::class, 'uploadFile'])->name('processFile');