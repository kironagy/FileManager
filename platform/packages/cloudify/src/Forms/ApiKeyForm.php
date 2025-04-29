<?php

namespace Botble\Cloudify\Forms;

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Cloudify\Actions\GenerateTokenAction;
use Botble\Cloudify\Enums\ApiKeyType;
use Botble\Cloudify\Enums\ExternalAbility;
use Botble\Cloudify\Enums\InternalAbility;
use Botble\Cloudify\Http\Requests\ApiKeyRequest;
use Botble\Cloudify\Models\ApiKey;

class ApiKeyForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(ApiKey::class)
            ->setValidatorClass(ApiKeyRequest::class)
            ->add(
                'token',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.token'))
                    ->when(! $this->getModel()->exists, fn (TextFieldOption $option) => $option->defaultValue((new GenerateTokenAction())->handle()))
                    ->required()
            )
            ->add(
                'type',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('packages/cloudify::cloudify.api_key.type'))
                    ->helperText(trans('packages/cloudify::cloudify.api_key.type_helper'))
                    ->choices(ApiKeyType::labels())
                    ->when($this->getModel()->exists, fn (RadioFieldOption $option) => $option->selected($this->getModel()->type))
                    ->defaultValue(ApiKeyType::EXTERNAL)
                    ->required()
            )
            ->add('abilities', HtmlField::class, HtmlFieldOption::make()->content(function () {
                $apiKey = $this->getModel();
                $externalAbilities = ExternalAbility::labels();
                $internalAbilities = InternalAbility::labels();

                return view(
                    'packages/cloudify::api-keys.partials.abilities',
                    compact('apiKey', 'externalAbilities', 'internalAbilities')
                )->render();
            }))
            ->add(
                'special',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->helperText(trans('packages/cloudify::cloudify.api_key.special_helper'))
                    ->label(trans('packages/cloudify::cloudify.api_key.special'))
            );
    }
}
