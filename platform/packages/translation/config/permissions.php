<?php

return [
    [
        'name' => 'Localization',
        'flag' => 'packages.translation',
        'parent_flag' => 'settings.index',
    ],
    [
        'name' => 'Locales',
        'flag' => 'translations.locales',
        'parent_flag' => 'packages.translation',
    ],
    [
        'name' => 'Translations',
        'flag' => 'translations.index',
        'parent_flag' => 'packages.translation',
    ],
    [
        'name' => 'Export Translations',
        'flag' => 'translations.export',
        'parent_flag' => 'tools.data-synchronize',
    ],
    [
        'name' => 'Import Translations',
        'flag' => 'translations.import',
        'parent_flag' => 'tools.data-synchronize',
    ],
];
