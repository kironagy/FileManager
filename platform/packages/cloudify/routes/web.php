<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Cloudify\Http\Controllers\ApiKeyController;
use Botble\Cloudify\Http\Controllers\Settings\ApiSettingController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::name('cloudify.')->group(function () {
        Route::group(['prefix' => 'cloudify/api-keys', 'as' => 'api-keys.'], function () {
            Route::resource('', ApiKeyController::class)->parameters(['' => 'key']);
        });

        Route::group(['prefix' => 'settings/cloudify', 'permission' => 'cloudify.api-settings.index'], function () {
            Route::get('api', [ApiSettingController::class, 'index'])->name('settings.api.index');
            Route::put('api', [ApiSettingController::class, 'update'])->name('settings.api.update');
        });
    });
});
