<?php

namespace Botble\Translation\PanelSections;

use Botble\Base\PanelSections\PanelSection;
use Botble\Base\PanelSections\PanelSectionItem;

class LocalizationPanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('others.localization')
            ->setTitle(trans('packages/translation::translation.localization'))
            ->withPriority(1000)
            ->withItems([
                PanelSectionItem::make('localization.locales')
                    ->setTitle(trans('packages/translation::translation.panel.locales.title'))
                    ->withIcon('ti ti-world-download')
                    ->withDescription(trans('packages/translation::translation.panel.locales.description'))
                    ->withPriority(10)
                    ->withRoute('translations.locales'),
                PanelSectionItem::make('localization.other_translation')
                    ->setTitle(trans('packages/translation::translation.panel.admin-translations.title'))
                    ->withIcon('ti ti-message-language')
                    ->withDescription(trans('packages/translation::translation.panel.admin-translations.description'))
                    ->withPriority(30)
                    ->withRoute('translations.index'),
            ]);
    }
}
