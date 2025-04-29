<?php

namespace Botble\Cloudify\Http\Controllers;

use Botble\Base\Supports\Breadcrumb;
use Botble\Cloudify\Forms\ApiKeyForm;
use Botble\Cloudify\Http\Requests\ApiKeyRequest;
use Botble\Cloudify\Models\ApiKey;
use Botble\Cloudify\Tables\ApiKeyTable;
use Botble\Setting\Http\Controllers\SettingController;

class ApiKeyController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('packages/cloudify::cloudify.api_key.title'), route('cloudify.api-keys.index'));
    }

    public function index(ApiKeyTable $apiTokenTable)
    {
        $this->pageTitle(trans('packages/cloudify::cloudify.api_key.title'));

        return $apiTokenTable->renderTable();
    }

    public function create(ApiKeyForm $apiTokenForm)
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return $apiTokenForm->create()->renderForm();
    }

    public function store(ApiKeyRequest $request)
    {
        $key = ApiKey::query()->create($request->validated());

        return $this
            ->httpResponse()
            ->setPreviousRoute('cloudify.api-keys.index')
            ->setNextRoute('cloudify.api-keys.edit', $key)
            ->withCreatedSuccessMessage();
    }

    public function edit(ApiKey $key, ApiKeyForm $apiTokenForm)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $key->token]));

        return $apiTokenForm->createFromModel($key)->renderForm();
    }

    public function update(ApiKey $key, ApiKeyRequest $request)
    {
        $key->update($request->validated());

        return $this
            ->httpResponse()
            ->setPreviousRoute('cloudify.api-keys.index')
            ->setNextRoute('cloudify.api-keys.edit', $key)
            ->withUpdatedSuccessMessage();
    }
}
