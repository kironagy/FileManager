<?php

namespace Botble\Cloudify\Enums;

use Botble\Base\Facades\Html;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

class ApiKeyType extends Enum
{
    public const EXTERNAL = 'external';

    public const INTERNAL = 'internal';

    protected static $langPath = 'packages/cloudify::cloudify.enums.api_key_type';

    public function toHtml(): HtmlString
    {
        return match ($this->value) {
            self::EXTERNAL => Html::tag('span', self::EXTERNAL()->label(), ['class' => 'badge bg-success text-success-fg']),
            self::INTERNAL => Html::tag('span', self::INTERNAL()->label(), ['class' => 'badge bg-warning text-warning-fg']),
            default => parent::toHtml(),
        };
    }
}
