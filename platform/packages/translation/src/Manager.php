<?php

namespace Botble\Translation;

use ArrayAccess;
use Botble\Base\Services\DeleteUnusedTranslationFilesService;
use Botble\Base\Services\DownloadLocaleService;
use Botble\Base\Supports\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Symfony\Component\VarExporter\VarExporter;
use Throwable;

class Manager
{
    protected array|ArrayAccess $config;

    protected DownloadLocaleService $downloadLocaleService;

    protected DeleteUnusedTranslationFilesService $deleteUnusedTranslationFilesService;

    public function __construct(protected Application $app, protected Filesystem $files)
    {
        $this->config = $app['config']['packages.translation.general'];

        $this->downloadLocaleService = new DownloadLocaleService();
        $this->deleteUnusedTranslationFilesService = new DeleteUnusedTranslationFilesService();
    }

    public function publishLocales(): void
    {
        $paths = ServiceProvider::pathsToPublish(null, 'cms-lang');

        foreach ($paths as $from => $to) {
            $this->files->ensureDirectoryExists(dirname($to));
            $this->files->copyDirectory($from, $to);
        }

        if (! $this->files->isDirectory(lang_path('en')) || $this->files->isEmptyDirectory(lang_path('en'))) {
            $this->downloadRemoteLocale('en');
        }
    }

    public function updateTranslation(string $locale, string $group, string|array $key, ?string $value = null): void
    {
        $loader = Lang::getLoader();

        $translationsArray = is_array($key) ? $key : [$key => $value];

        if (str_contains($group, DIRECTORY_SEPARATOR)) {
            $englishTranslations = $loader->load('en', Str::afterLast($group, DIRECTORY_SEPARATOR), Str::beforeLast($group, DIRECTORY_SEPARATOR));
            $translations = $loader->load($locale, Str::afterLast($group, DIRECTORY_SEPARATOR), Str::beforeLast($group, DIRECTORY_SEPARATOR));
        } else {
            $englishTranslations = $loader->load('en', $group);
            $translations = $loader->load($locale, $group);
        }

        foreach ($translationsArray as $transKey => $transValue) {
            Arr::set($translations, $transKey, $transValue);
        }

        $translations = array_merge($englishTranslations, $translations);

        $file = $locale . '/' . $group;

        File::ensureDirectoryExists(lang_path($locale));

        $groups = explode(DIRECTORY_SEPARATOR, $group);
        if (count($groups) > 1) {
            $folderName = Arr::last($groups);
            Arr::forget($groups, count($groups) - 1);

            $dir = 'vendor/' . implode('/', $groups) . '/' . $locale;
            File::ensureDirectoryExists(lang_path($dir));

            $file = $dir . '/' . $folderName;
        }

        $path = lang_path($file . '.php');
        $output = "<?php\n\nreturn " . VarExporter::export($translations) . ";\n";

        File::put($path, $output);
    }

    public function getConfig(?string $key = null): string|array|null
    {
        if ($key == null) {
            return $this->config;
        }

        return $this->config[$key];
    }

    public function getRemoteAvailableLocales(): array
    {
        return $this->downloadLocaleService->getAvailableLocales();
    }

    public function downloadRemoteLocale(string $locale): array
    {
        $this->ensureAllDirectoriesAreCreated();

        try {
            $this->downloadLocaleService->handle($locale);
        } catch (Throwable $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        $this->deleteUnusedTranslationFilesService->handle();

        return [
            'error' => false,
            'message' => 'Downloaded translation files!',
        ];
    }

    public function ensureAllDirectoriesAreCreated(): void
    {
        $this->files->ensureDirectoryExists(lang_path('vendor'));
        $this->files->ensureDirectoryExists(lang_path('vendor/core'));
        $this->files->ensureDirectoryExists(lang_path('vendor/packages'));
        $this->files->ensureDirectoryExists(lang_path('vendor/packages'));
    }
}
