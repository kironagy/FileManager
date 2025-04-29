<?php

namespace Botble\Cloudify\Models;

use Botble\Base\Models\BaseModel;
use Botble\Cloudify\Enums\ApiKeyType;

class ApiKey extends BaseModel
{
    protected $table = 'api_keys';

    protected $guarded = [];

    protected $casts = [
        'type' => ApiKeyType::class,
        'abilities' => 'json',
        'special' => 'bool',
    ];
}
