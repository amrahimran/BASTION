<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ActivityHistoryController;

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


Route::get('/scan', [ScanController::class, 'showScanPage'])->name('scan');
Route::post('/scan/run', [ScanController::class, 'runScan'])->name('scan.run');
Route::get('/scan/reports', [ScanController::class, 'reports'])->name('scan.reports');
Route::get('/scan/export/csv', [ScanController::class, 'exportCsv'])->name('scan.export.csv');

Route::get('/scan/{scan}/export', [ScanController::class, 'exportSingleCsv'])->name('scan.export.single');

Route::get('/scan/export/csv', [ScanController::class, 'exportCsv'])->name('scan.export.csv');

Route::get('/scan/result/{id}', [ScanController::class, 'showScanResult'])->name('scan.result');
Route::get('/scan/export/{id}', [ScanController::class, 'exportSingleCsv'])->name('scan.export.single.csv');

Route::get('/scans/{id}/pdf', [ScanController::class, 'exportSinglePdf'])->name('scans.export.pdf');
Route::get('/scans/pdf/all', [ScanController::class, 'exportAllPdf'])->name('scans.export.pdf.all');

Route::get('/activity-history', [ActivityHistoryController::class, 'index'])
    ->middleware('auth')
    ->name('activity.history');

    Route::get('/test-cohere', function () {
    dd(env('COHERE_API_KEY'));
});

// Route::get('/simulation', function () {
// return view('profile.simulation'); // Make sure simulation.blade.php is in resources/views/profile/
// })->name('simulation');


// use App\Http\Controllers\SimulationController;

// Route::middleware(['auth'])->group(function () {

//     Route::prefix('simulation')->group(function () {
//         Route::post('/run/mitm', [SimulationController::class, 'runMitm']);
//         Route::get('/mitm/result/{id}', [SimulationController::class, 'showMitmResult'])
//             ->name('simulation.mitm.result');
//     });

//     Route::get('profile/simulationreports', [SimulationController::class, 'reports'])
//         ->name('simulation.reports');
// });


use App\Http\Controllers\SimulationController;

Route::middleware(['auth'])->group(function () {

    Route::get('/simulation', [SimulationController::class, 'index'])
        ->name('simulation.index');

    Route::post('/simulation/run/mitm', [SimulationController::class, 'runMitm'])
        ->name('mitm.run');

    Route::post('/simulation/run/ddos', [SimulationController::class, 'runDdos'])
    ->name('ddos.run');

    Route::post('/simulation/phishing/run', [SimulationController::class, 'runPhishing'])
    ->name('phishing.run');


    Route::get('/simulation/result/{simulation}', [SimulationController::class, 'result'])
        ->name('simulation.result');

});

Route::prefix('simulations')->middleware('auth')->group(function() {
    Route::get('/reports', [SimulationController::class, 'reports'])->name('simulation.reports');
    Route::get('/export/pdf/all', [SimulationController::class, 'exportAllPdf'])->name('simulations.export.pdf.all');
    Route::get('/export/pdf/{id}', [SimulationController::class, 'exportSinglePdf'])->name('simulations.export.pdf.single');
});
