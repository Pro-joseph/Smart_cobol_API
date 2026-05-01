<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CobolController;

Route::get('/', [CobolController::class, 'index']);
Route::post('/generate', [CobolController::class, 'generate']);
Route::post('/cobol/test', [CobolController::class, 'test']);

Route::post('/cobol/run', [CobolController::class, 'run']);