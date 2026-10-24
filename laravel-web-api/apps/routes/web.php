<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin RBAC Routes
Route::middleware(['auth', 'verified', 'role:super-admin|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserRoleController::class)->only(['index', 'edit', 'update']);
});

require __DIR__.'/auth.php';


// ************************************************* 
// Database Migration Routes (For Development Only)
// *************************************************
Route::middleware('auth.basic')->group(function () {
    // 1. Run Standard Migration (Safe for live data)
    Route::get('/db-migrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        return response("<pre>Database Migrated:\n\n" . Artisan::output() . "</pre>");
    });
    // 2. Fresh Migration Only (Wipes all data)
    Route::get('/db-fresh', function () {
        Artisan::call('migrate:fresh', ['--force' => true]);
        return response("<pre>Database Wiped & Rebuilt:\n\n" . Artisan::output() . "</pre>");
    });
    // 3. Fresh Migration + Database Seeding (Wipes all data)
    Route::get('/db-fresh-seed', function () {
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        return response("<pre>Database Wiped, Rebuilt & Seeded:\n\n" . Artisan::output() . "</pre>");
    });

    Route::get('/clear-cache', function() {
        Artisan::call('optimize:clear');
        return response("<pre>Server optimized and cache cleared successfully!:\n\n" . Artisan::output() . "</pre>");
    });
});