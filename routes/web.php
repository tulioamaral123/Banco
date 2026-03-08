<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Catch-all: serve the SPA for every route (Vue Router handles client-side navigation)
Route::get('/{any}', [DashboardController::class, 'index'])->where('any', '.*');
