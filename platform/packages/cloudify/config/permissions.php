<?php

return [
    [
        'name' => 'Cloudify',
        'flag' => 'cloudify.index',
        'parent_flag' => 'settings.index',
    ],
    [
        'name' => 'API Settings',
        'flag' => 'cloudify.api-settings.index',
        'parent_flag' => 'cloudify.index',
    ],
    [
        'name' => 'API Keys',
        'flag' => 'cloudify.api-keys.index',
        'parent_flag' => 'cloudify.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'cloudify.api-keys.create',
        'parent_flag' => 'cloudify.api-keys.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'cloudify.api-keys.edit',
        'parent_flag' => 'cloudify.api-keys.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'cloudify.api-keys.destroy',
        'parent_flag' => 'cloudify.api-keys.index',
    ],
];
