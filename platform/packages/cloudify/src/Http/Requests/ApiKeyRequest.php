<?php

namespace Botble\Cloudify\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Cloudify\Enums\ApiKeyType;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ApiKeyRequest extends Request
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'max:200'],
            'type' => ['required', Rule::in(ApiKeyType::values())],
            'abilities' => ['nullable', 'array'],
            'abilities.*' => ['required', 'string'],
            'special' => [new OnOffRule()],
        ];
    }
}
