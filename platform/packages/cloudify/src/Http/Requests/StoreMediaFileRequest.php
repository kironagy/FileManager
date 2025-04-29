<?php

namespace Botble\Cloudify\Http\Requests;

use Botble\Support\Http\Requests\Request;

class StoreMediaFileRequest extends Request
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file'],
        ];
    }
}
