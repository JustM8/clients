<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🧑‍💼 Створюємо лише адміністратора
        User::factory()->admin()->create();
    }
}
