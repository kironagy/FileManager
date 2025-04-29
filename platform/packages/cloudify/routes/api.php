<?php

use Botble\Cloudify\Http\Controllers\MediaFileController;
use Botble\Cloudify\Http\Controllers\MediaFolderController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware((array) config('packages.cloudify.cloudify.middleware', []))->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('media')->group(function () {
            Route::prefix('folders')
                ->controller(MediaFolderController::class)
                ->group(function () {
                    Route::get('/', 'index');
                    Route::post('', 'store');
                    Route::patch('{folder}/trash', 'trash');
                    Route::delete('{folder}', 'destroy');
                    Route::post('deletes', 'deletes');
                });

            Route::prefix('files')
                ->controller(MediaFileController::class)
                ->group(function () {
                    Route::get('/', 'index');
                    Route::post('', 'store');
                    Route::patch('{folder}/trash', 'trash');
                    Route::delete('{file}', 'destroy');
                    Route::post('deletes', 'deletes');
                });
        });
    });
});
