<?php

namespace Database\Seeders;

use Botble\ACL\Database\Seeders\UserSeeder;
use Botble\Base\Facades\AdminAppearance;
use Botble\Base\Supports\BaseSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);

        setting()->forceSet([
            'media_random_hash' => md5((string) time()),
            AdminAppearance::getSettingKey('layout') => 'horizontal',
            AdminAppearance::getSettingKey('container_width') => 'container-fluid',
        ])->save();
    }
}
