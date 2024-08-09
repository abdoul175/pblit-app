<?php

use App\Http\Controllers\ImportDataController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImportDataController::class, 'index']);
Route::get('/export', [ImportDataController::class, 'export_form']);
Route::get('/delete-export-data', [ImportDataController::class, 'delete_export_data']);

Route::post('/import', [ImportDataController::class, 'import'])->name('import');
Route::post('/export', [ImportDataController::class, 'exporting'])->name('export');
