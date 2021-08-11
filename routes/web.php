<?php

use Illuminate\Support\Facades\Route;
use CharlGottschalk\FeatureToggle\Http\Controllers\FeaturesController;
use CharlGottschalk\FeatureToggle\Http\Middleware\SanitizeInput;

Route::get('/', [FeaturesController::class, 'index'])->name('features.toggle.index');
Route::get('/{id}/edit', [FeaturesController::class, 'edit'])->name('features.toggle.edit');
Route::get('/{id}/enable', [FeaturesController::class, 'enable'])->name('features.toggle.enable');
Route::get('/{id}/disable', [FeaturesController::class, 'disable'])->name('features.toggle.disable');
Route::get('/{id}/delete', [FeaturesController::class, 'delete'])->name('features.toggle.delete');
Route::post('/', [FeaturesController::class, 'store'])->name('features.toggle.store')->middleware(SanitizeInput::class);
Route::post('/{id}/edit', [FeaturesController::class, 'update'])->name('features.toggle.update')->middleware(SanitizeInput::class);
