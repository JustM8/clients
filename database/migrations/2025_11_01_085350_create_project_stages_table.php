<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stage_key')->default('custom');

            $table->unsignedInteger('position')->default(1);
            $table->unsignedTinyInteger('avg_duration_days')->default(0);

            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('project_stages');
    }
};
