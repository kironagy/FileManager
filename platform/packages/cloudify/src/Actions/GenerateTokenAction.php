<?php

namespace Botble\Cloudify\Actions;

use Illuminate\Support\Str;

class GenerateTokenAction
{
    public function handle(): string
    {
        return strtoupper(hash('sha1', Str::orderedUuid()->toString()));
    }
}
