<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Subhashladumor\LaravelMulticloud\Http\Controllers\CloudController;

/*
|--------------------------------------------------------------------------
| MultiCloud API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for the Laravel MultiCloud package.
| These routes provide HTTP endpoints for cloud operations.
|
*/

Route::prefix('api/multicloud')->group(function () {
    // File operations
    Route::post('/upload', [CloudController::class, 'upload']);
    Route::get('/download', [CloudController::class, 'download']);
    Route::delete('/delete', [CloudController::class, 'delete']);
    Route::get('/list', [CloudController::class, 'list']);
    Route::get('/exists', [CloudController::class, 'exists']);
    Route::get('/metadata', [CloudController::class, 'metadata']);
    Route::get('/signed-url', [CloudController::class, 'signedUrl']);
    
    // Provider operations
    Route::get('/usage', [CloudController::class, 'usage']);
    Route::get('/test-connection', [CloudController::class, 'testConnection']);
    Route::get('/providers', [CloudController::class, 'providers']);
});
