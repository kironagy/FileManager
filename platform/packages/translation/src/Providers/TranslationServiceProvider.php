<?php

namespace Botble\Translation\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\DataSynchronize\PanelSections\ExportPanelSection;
use Botble\DataSynchronize\PanelSections\ImportPanelSection;
use Botble\Translation\Console\DownloadLocaleCommand;
use Botble\Translation\Console\RemoveLocaleCommand;
use Botble\Translation\Console\RemoveUnusedTranslationsCommand;
use Botble\Translation\PanelSections\LocalizationPanelSection;

class TranslationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('packages/translation')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadMigrations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        PanelSectionManager::beforeRendering(function () {
            PanelSectionManager::register(LocalizationPanelSection::class);
        });

        PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function () {
            PanelSectionManager::default()
                ->registerItem(
                    ExportPanelSection::class,
                    fn () => PanelSectionItem::make('translations')
                        ->setTitle(trans('packages/translation::translation.panel.admin-translations.title'))
                        ->withDescription(trans(
                            'packages/translation::translation.export_description',
                            ['name' => trans('packages/translation::translation.panel.admin-translations.title')]
                        ))
                        ->withPriority(999)
                        ->withPermission('translations.export')
                        ->withRoute('tools.data-synchronize.export.translations.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('translations')
                        ->setTitle(trans('packages/translation::translation.panel.admin-translations.title'))
                        ->withDescription(trans(
                            'packages/translation::translation.import_description',
                            ['name' => trans('packages/translation::translation.panel.admin-translations.title')]
                        ))
                        ->withPriority(999)
                        ->withPermission('translations.import')
                        ->withRoute('tools.data-synchronize.import.translations.index')
                );
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                RemoveUnusedTranslationsCommand::class,
                DownloadLocaleCommand::class,
                RemoveLocaleCommand::class,
            ]);
        }
    }
}
