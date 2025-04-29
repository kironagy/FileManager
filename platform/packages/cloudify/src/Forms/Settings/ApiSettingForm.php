<?php

namespace Botble\Cloudify\Forms\Settings;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\TagFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\TagField;
use Botble\Cloudify\Http\Requests\Settings\ApiSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ApiSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setValidatorClass(ApiSettingRequest::class)
            ->columns()
            ->setSectionTitle(trans('packages/cloudify::cloudify.setting.title'))
            ->setSectionDescription(trans('packages/cloudify::cloudify.setting.description'))
            ->add(
                'cloudify_blacklist_domain_failed_attempts',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.setting.blacklist_domain_failed_attempts'))
                    ->value(setting('cloudify_blacklist_domain_failed_attempts'))
                    ->placeholder(trans('packages/cloudify::cloudify.api_key.setting.blacklist_domain_failed_attempts_placeholder'))
                    ->helperText(trans('packages/cloudify::cloudify.api_key.setting.blacklist_domain_failed_attempts_helper'))
                    ->colspan(1)
            )
            ->add(
                'cloudify_blacklist_ip_failed_attempts',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.setting.blacklist_ip_failed_attempts'))
                    ->value(setting('cloudify_blacklist_ip_failed_attempts'))
                    ->placeholder(trans('packages/cloudify::cloudify.api_key.setting.blacklist_ip_failed_attempts_placeholder'))
                    ->helperText(trans('packages/cloudify::cloudify.api_key.setting.blacklist_ip_failed_attempts_helper'))
                    ->colspan(1)
            )
            ->add(
                'cloudify_api_rate_limiting_method',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.setting.api_rate_limiting_method'))
                    ->choices([
                        'ip_address' => trans('packages/cloudify::cloudify.api_key.setting.limit_method.ip_address'),
                        'api_token' => trans('packages/cloudify::cloudify.api_key.setting.limit_method.api_token'),
                    ])
                    ->selected(setting('cloudify_api_rate_limiting_method', 'ip_address'))
                    ->required()
                    ->colspan(1)
            )
            ->add(
                'cloudify_api_requests_rate_limit_per_hour',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.setting.api_requests_rate_limit_per_hour'))
                    ->placeholder(trans('packages/cloudify::cloudify.api_key.setting.api_requests_rate_limit_per_hour_placeholder'))
                    ->helperText(trans('packages/cloudify::cloudify.api_key.setting.api_requests_rate_limit_per_hour_helper'))
                    ->value(setting('cloudify_api_requests_rate_limit_per_hour'))
                    ->colspan(1)
            )
            ->add(
                'cloudify_api_blacklisted_domains',
                TagField::class,
                TagFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.setting.api_blacklisted_domains'))
                    ->value(setting('cloudify_api_blacklisted_domains'))
                    ->placeholder(trans('packages/cloudify::cloudify.api_key.setting.api_blacklisted_domains_placeholder'))
                    ->helperText(trans('packages/cloudify::cloudify.api_key.setting.api_blacklisted_domains_helper'))
                    ->colspan(1)
            )
            ->add(
                'cloudify_api_blacklisted_ips',
                TagField::class,
                TagFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.setting.api_blacklisted_ips'))
                    ->value(setting('cloudify_api_blacklisted_ips'))
                    ->placeholder(trans('packages/cloudify::cloudify.api_key.setting.api_blacklisted_ips_placeholder'))
                    ->helperText(trans('packages/cloudify::cloudify.api_key.setting.api_blacklisted_ips_helper'))
                    ->colspan(1)
            );
    }
}
