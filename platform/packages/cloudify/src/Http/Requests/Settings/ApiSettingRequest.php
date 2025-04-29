<?php

namespace Botble\Cloudify\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class ApiSettingRequest extends Request
{
    protected const DOMAIN_REGEX = '/^(?:[a-z0-9](?:[a-z0-9-æøå]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/isu';

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cloudify_api_blacklisted_domains' => array_map(fn ($item) => $item['value'], json_decode($this->input('cloudify_api_blacklisted_domains') ?: '[]', true)),
            'cloudify_api_blacklisted_ips' => array_map(fn ($item) => $item['value'], json_decode($this->input('cloudify_api_blacklisted_ips') ?: '[]', true)),
        ]);
    }

    public function rules(): array
    {
        return [
            'cloudify_blacklist_domain_failed_attempts' => ['nullable', 'integer', 'min:1'],
            'cloudify_blacklist_ip_failed_attempts' => ['nullable', 'integer', 'min:1'],
            'cloudify_api_rate_limiting_method' => ['required', 'string', 'in:ip_address,api_token'],
            'cloudify_api_requests_rate_limit_per_hour' => ['nullable', 'integer', 'min:1'],
            'cloudify_api_blacklisted_domains' => ['nullable'],
            'cloudify_api_blacklisted_domains.*' => ['nullable', 'regex:' . self::DOMAIN_REGEX],
            'cloudify_api_blacklisted_ips' => ['nullable'],
            'cloudify_api_blacklisted_ips.*' => ['nullable', 'ip'],
        ];
    }
}
