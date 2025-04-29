<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Media\Facades\RvMedia;

class RandomImageSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('avatars');
        $this->uploadFiles('lorem');

        for ($i = 0; $i < 100; $i++) {
            RvMedia::uploadFromUrl('https://picsum.photos/1500/1500');
        }

        RvMedia::createFolder('grayscale');

        for ($i = 0; $i < 10; $i++) {
            RvMedia::uploadFromUrl('https://picsum.photos/1500/1500?grayscale', 'grayscale');
        }

        RvMedia::createFolder('blue');

        for ($i = 0; $i < 10; $i++) {
            RvMedia::uploadFromUrl('https://picsum.photos/1500/1500?blur', 'blur');
        }

        RvMedia::createFolder('random');

        for ($i = 0; $i < 10; $i++) {
            RvMedia::uploadFromUrl('https://picsum.photos/1500/1500', 'random');
        }
    }
}
