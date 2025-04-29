<?php

namespace Botble\Cloudify\Concerns;

use Botble\Cloudify\Models\ApiKey;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

trait HasAbility
{
    public function checkAbility(string $ability): Response
    {
        $token = request()->header('X-API-KEY');

        $apiToken = ApiKey::query()
            ->where('token', $token)
            ->first();

        return Gate::allowIf($apiToken && in_array($ability, $apiToken->abilities));
    }
}
