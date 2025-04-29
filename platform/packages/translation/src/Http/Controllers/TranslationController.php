<?php

namespace Botble\Translation\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Setting\Http\Controllers\SettingController;
use Botble\Translation\Http\Controllers\Concerns\HasMapTranslationsTable;
use Botble\Translation\Http\Requests\TranslationRequest;
use Botble\Translation\Manager;
use Botble\Translation\Tables\TranslationTable;
use Illuminate\Http\Request;

class TranslationController extends SettingController
{
    use HasMapTranslationsTable;

    public function __construct(protected Manager $manager)
    {
    }

    public function index(Request $request, TranslationTable $translationTable)
    {
        $this->pageTitle(trans('packages/translation::translation.admin-translations'));

        Assets::addScriptsDirectly('vendor/core/packages/translation/js/translation.js')
            ->addStylesDirectly('vendor/core/packages/translation/css/translation.css');

        [$locales, $locale, $defaultLanguage, $translationTable]
            = $this->mapTranslationsTable($translationTable, $request);

        if ($request->expectsJson()) {
            return $translationTable->renderTable();
        }

        return view(
            'packages/translation::index',
            compact('locales', 'locale', 'defaultLanguage', 'translationTable')
        );
    }

    public function update(TranslationRequest $request)
    {
        $group = $request->input('group');

        $name = $request->input('name');
        $value = $request->input('value');

        [$locale, $key] = explode('|', $name, 2);

        $this->manager->updateTranslation($locale, $group, $key, $value);

        return $this->httpResponse();
    }
}
