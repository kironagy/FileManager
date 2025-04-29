<?php

namespace Botble\Cloudify\Tables;

use Botble\Cloudify\Enums\ExternalAbility;
use Botble\Cloudify\Models\ApiKey;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\DateTimeColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\Columns\YesNoColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;

class ApiKeyTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(ApiKey::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('cloudify.api-keys.create'))
            ->addBulkAction(DeleteBulkAction::make())
            ->addActions([
                EditAction::make()->route('cloudify.api-keys.edit'),
                DeleteAction::make()->route('cloudify.api-keys.destroy'),
            ])
            ->addColumns([
                FormattedColumn::make('token')
                    ->label(trans('packages/cloudify::cloudify.api_key.token'))
                    ->limit(20)
                    ->copyable(),
                StatusColumn::make('type')
                    ->label(trans('packages/cloudify::cloudify.api_key.type')),
                YesNoColumn::make('special')
                    ->trans('packages/cloudify::cloudify.api_key.special'),
                FormattedColumn::make('abilities')
                    ->label(trans('packages/cloudify::cloudify.api_key.abilities'))
                    ->width('30%')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $abilities = $column->getItem()->abilities;

                        if (empty($abilities)) {
                            return null;
                        }

                        return implode(', ', array_map(fn ($ability) => ExternalAbility::getLabel($ability), $abilities));
                    })
                    ->withEmptyState(),
                DateTimeColumn::make('created_at'),
            ]);
    }
}
