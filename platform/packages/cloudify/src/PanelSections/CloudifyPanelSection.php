<?php

namespace Botble\Cloudify\PanelSections;

use Botble\Base\PanelSections\PanelSection;
use Botble\Base\PanelSections\PanelSectionItem;

class CloudifyPanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('cloudify')
            ->setTitle(trans('packages/cloudify::cloudify.title'))
            ->withPriority(99998)
            ->withItems([
                PanelSectionItem::make('cloudify.api-settings')
                    ->setTitle(trans('packages/cloudify::cloudify.setting.title'))
                    ->withIcon('ti ti-api')
                    ->withDescription(trans('packages/cloudify::cloudify.setting.description'))
                    ->withPriority(10)
                    ->withRoute('cloudify.settings.api.index'),
                PanelSectionItem::make('cloudify.api-keys')
                    ->setTitle(trans('packages/cloudify::cloudify.api_key.title'))
                    ->withIcon('ti ti-key')
                    ->withDescription(trans('packages/cloudify::cloudify.api_key.setting.description'))
                    ->withPriority(10)
                    ->withRoute('cloudify.api-keys.index'),
            ]);
    }
}
