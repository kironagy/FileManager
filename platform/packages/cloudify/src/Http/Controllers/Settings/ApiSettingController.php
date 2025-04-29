<?php

namespace Botble\Cloudify\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Cloudify\Forms\Settings\ApiSettingForm;
use Botble\Cloudify\Http\Requests\Settings\ApiSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class ApiSettingController extends SettingController
{
    public function index(ApiSettingForm $apiSettingForm): string
    {
        $this->pageTitle(trans('packages/cloudify::cloudify.setting.title'));

        return $apiSettingForm->create()->renderForm();
    }

    public function update(ApiSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
