<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/scan', function () {
    return view('profile.scan');
})->name('scan');

Route::post('/scan/run', [ScanController::class, 'runScan'])->name('scan.run');

Route::get('/scan/auto-detect', [ScanController::class, 'autoDetect'])->name('scan.autodetect');

