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

// Route::get('/scan', function () {
//     return view('profile.scan');
// })->name('scan');

Route::post('/scan/run', [ScanController::class, 'runScan'])->name('scan.run');

Route::get('/scan/auto-detect', [ScanController::class, 'autoDetect'])->name('scan.autodetect');

use App\Http\Controllers\AdminUserController;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

// Route::get('/scan', [ScanController::class, 'showScanPage'])->name('scan');
// Route::post('/scan/run', [ScanController::class, 'runScan'])->name('scan.run');
// Route::get('/scan/reports', [ScanController::class, 'reports'])->name('scan.reports');
// Route::get('/scan/export/csv', [ScanController::class, 'exportCsv'])->name('scan.export.csv');

Route::get('/scan', [ScanController::class, 'showScanPage'])->name('scan');
Route::post('/scan/run', [ScanController::class, 'runScan'])->name('scan.run');
Route::get('/scan/reports', [ScanController::class, 'reports'])->name('scan.reports');
Route::get('/scan/export/csv', [ScanController::class, 'exportCsv'])->name('scan.export.csv');

Route::get('/scan/{scan}/export', [ScanController::class, 'exportSingleCsv'])->name('scan.export.single');
