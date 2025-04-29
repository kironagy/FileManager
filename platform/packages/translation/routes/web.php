<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Translation\Http\Controllers\ExportOtherTranslationController;
use Botble\Translation\Http\Controllers\ImportOtherTranslationController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Translation\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'translations'], function () {
            Route::group(['prefix' => 'locales', 'permission' => 'translations.locales'], function () {
                Route::get('', [
                    'as' => 'translations.locales',
                    'uses' => 'LocaleController@index',
                ]);

                Route::post('', [
                    'as' => 'translations.locales.post',
                    'uses' => 'LocaleController@store',
                    'middleware' => 'preventDemo',
                ]);

                Route::delete('{locale}', [
                    'as' => 'translations.locales.delete',
                    'uses' => 'LocaleController@destroy',
                    'middleware' => 'preventDemo',
                ]);

                Route::get('download/{locale}', [
                    'as' => 'translations.locales.download',
                    'uses' => 'LocaleController@download',
                    'middleware' => 'preventDemo',
                ]);
            });

            Route::group(['permission' => 'translations.index'], function () {
                Route::match(['GET', 'POST'], '', [
                    'as' => 'translations.index',
                    'uses' => 'TranslationController@index',
                ]);

                Route::post('edit', [
                    'as' => 'translations.group.edit',
                    'uses' => 'TranslationController@update',
                    'middleware' => 'preventDemo',
                ]);
            });
        });

        Route::prefix('tools/data-synchronize')->name('tools.data-synchronize.')->group(function () {
            Route::prefix('export')->name('export.')->group(function () {
                Route::group(['prefix' => 'translations', 'as' => 'translations.', 'permission' => 'translations.export'], function () {
                    Route::get('/', [ExportOtherTranslationController::class, 'index'])->name('index');
                    Route::post('/', [ExportOtherTranslationController::class, 'store'])->name('store');
                });
            });

            Route::prefix('import')->name('import.')->group(function () {
                Route::group(['prefix' => 'translations', 'as' => 'translations.', 'permission' => 'translations.import'], function () {
                    Route::get('/', [ImportOtherTranslationController::class, 'index'])->name('index');
                    Route::post('/', [ImportOtherTranslationController::class, 'import'])->name('store');
                    Route::post('validate', [ImportOtherTranslationController::class, 'validateData'])->name('validate');
                });
            });
        });
    });
});
