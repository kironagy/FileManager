<?php

namespace Botble\Translation\Importers;

use Botble\Base\Supports\Language;
use Botble\DataSynchronize\Contracts\Importer\WithMapping;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Translation\Manager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OtherTranslationImporter extends Importer implements WithMapping
{
    public function chunkSize(): int
    {
        return 1000;
    }

    public function getLabel(): string
    {
        return trans('packages/translation::translation.panel.admin-translations.title');
    }

    public function columns(): array
    {
        $columns = [
            ImportColumn::make('key')
                ->rules(['required', 'string'], trans('packages/translation::translation.import.rules.key')),
            ImportColumn::make('english')
                ->rules(
                    ['nullable', 'string', 'max:10000'],
                    trans(
                        'packages/translation::translation.import.rules.trans',
                        ['max' => 10000]
                    )
                ),
        ];

        foreach (Language::getAvailableLocales() as $locale) {
            if ($locale['locale'] === 'en') {
                continue;
            }

            $columns[] = ImportColumn::make(Str::snake($locale['name']))
                ->rules(
                    ['nullable', 'string', 'max:10000'],
                    trans(
                        'packages/translation::translation.import.rules.trans',
                        ['max' => 10000]
                    )
                );
        }

        return $columns;
    }

    public function getValidateUrl(): string
    {
        return route('tools.data-synchronize.import.translations.validate');
    }

    public function getImportUrl(): string
    {
        return route('tools.data-synchronize.import.translations.store');
    }

    public function getExportUrl(): ?string
    {
        return Auth::user()->hasPermission('translations.export')
            ? route('tools.data-synchronize.export.translations.store')
            : null;
    }

    public function map(mixed $row): array
    {
        [$group, $key] = explode('::', $row['key']);

        return [
            ...$row,
            'key' => $key,
            'group' => $group,
        ];
    }

    public function handle(array $data): int
    {
        $count = 0;

        $manager = app(Manager::class);

        $data = collect($data)->groupBy('group');

        foreach ($data as $group => $translations) {
            foreach (Language::getAvailableLocales() as $locale) {
                $localeTranslations = $translations->pluck($this->getLocaleName($locale['name']), 'key');

                if ($locale['locale'] !== 'vi') {
                    continue;
                }

                $manager->updateTranslation(
                    $locale['locale'],
                    $group,
                    $localeTranslations->all()
                );

                $count += count($localeTranslations);
            }
        }

        return $count;
    }

    protected function getLocaleName(string $key): ?string
    {
        foreach (Language::getAvailableLocales() as $locale) {
            if ($locale['name'] === $key) {
                return Str::snake($locale['name']);
            }
        }

        return null;
    }
}
