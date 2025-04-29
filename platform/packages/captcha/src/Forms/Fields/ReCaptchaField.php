<?php

namespace Botble\Captcha\Forms\Fields;

use Botble\Base\Forms\FormField;

class ReCaptchaField extends FormField
{
    protected function getTemplate(): string
    {
        return 'packages/captcha::forms.fields.recaptcha';
    }
}
