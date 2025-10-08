<?php
// routes/web.php

use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GrievanceController as AdminGrievanceController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('grievances.create');
});

Route::get('/grievances/create', [GrievanceController::class, 'create'])->name('grievances.create');
Route::post('/grievances', [GrievanceController::class, 'store'])->name('grievances.store');
Route::get('/grievances/success/{referenceNumber}', [GrievanceController::class, 'success'])->name('grievances.success');

// Authentication routes
Auth::routes(['register' => false]); // Disable public registration

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Grievances
    Route::get('/grievances', [AdminGrievanceController::class, 'index'])->name('grievances.index');
    Route::get('/grievances/{grievance}', [AdminGrievanceController::class, 'show'])->name('grievances.show');
    Route::put('/grievances/{grievance}', [AdminGrievanceController::class, 'update'])->name('grievances.update');
    Route::delete('/grievances/{grievance}', [AdminGrievanceController::class, 'destroy'])->name('grievances.destroy')
        ->middleware('super_admin');
    
    // Attachments
    Route::get('/attachments/{attachment}/download', [AdminGrievanceController::class, 'downloadAttachment'])
        ->name('attachments.download');
    
    // Categories (Super Admin only)
    Route::middleware('super_admin')->group(function () {
        Route::resource('categories', CategoryController::class);
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
