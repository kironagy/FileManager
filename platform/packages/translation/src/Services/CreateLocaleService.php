<?php

namespace Botble\Translation\Services;

use Botble\Translation\Manager;
use Illuminate\Support\Facades\File;

class CreateLocaleService
{
    public function handle(string $locale): void
    {
        $manager = app(Manager::class);

        $result = $manager->downloadRemoteLocale($locale);

        $manager->publishLocales();

        if ($result['error']) {
            $defaultLocale = lang_path('en');

            if (File::exists($defaultLocale)) {
                File::copyDirectory($defaultLocale, lang_path($locale));
            }

            $this->createLocaleFiles(lang_path('vendor/core'), $locale);
            $this->createLocaleFiles(lang_path('vendor/packages'), $locale);

            File::deleteDirectory(lang_path('vendor/plugins'));
            File::deleteDirectory(lang_path('vendor/themes'));

            foreach (File::directories(lang_path('vendor/packages')) as $module) {
                if (! File::isDirectory(package_path(File::basename($module)))) {
                    File::deleteDirectory($module);
                }
            }
        }
    }

    protected function createLocaleFiles(string $path, string $locale): void
    {
        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $item) {
                if (File::name($item) == 'en') {
                    File::copyDirectory($item, $module . '/' . $locale);
                }
            }
        }
    }
}
