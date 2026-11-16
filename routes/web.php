<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('sites.index');
Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
Route::get('/sites/{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');
Route::put('/sites/{site}', [SiteController::class, 'update'])->name('sites.update');
Route::post('/sites/{site}/regenerate', [SiteController::class, 'regenerate'])->name('sites.regenerate');
Route::post('/sites/{site}/publish', [SiteController::class, 'publish'])->name('sites.publish');
Route::delete('/sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');
Route::get('/sites/{site}/preview', [SiteController::class, 'preview'])->name('sites.preview');
Route::get('/sites/{site}/download', [SiteController::class, 'download'])->name('sites.download');
Route::get('/s/{slug}', [SiteController::class, 'show'])->name('sites.show');
