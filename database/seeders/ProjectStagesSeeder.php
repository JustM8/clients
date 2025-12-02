<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectStage;

class ProjectStagesSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'Збір інформації',    'stage_key' => 'collect',  'position' => 1, 'avg_duration_days' => 2],
            ['name' => 'Обробка інформації', 'stage_key' => 'process',  'position' => 2, 'avg_duration_days' => 3],
            ['name' => 'Виробництво',        'stage_key' => 'produce',  'position' => 3, 'avg_duration_days' => 5],
            ['name' => 'Прийняття роботи',   'stage_key' => 'accept',   'position' => 4, 'avg_duration_days' => 1],
            ['name' => 'Оплата',             'stage_key' => 'pay',      'position' => 5, 'avg_duration_days' => 1],
        ];

        foreach ($stages as $stage) {
            ProjectStage::create($stage);
        }
    }
}
