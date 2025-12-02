<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectType;

class ProjectTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Вебсайт',
            'Лендінг',
            'Рендери інтер\'єрні',
            'Рендери екстер\'єрні',
            '2D планування',
            '3D планування',
            '3D відео',
            '360-відео-сфера',
            '360 тур',
            'Spinhouse',
            'Devbase',
        ];

        foreach ($types as $type) {
            ProjectType::firstOrCreate(['name' => $type]);
        }
    }
}
