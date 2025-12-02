<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimer extends Model
{

    protected $fillable = [
        'project_id',
        'user_id', // ✅ ось цього бракує
        'started_at',
        'stopped_at',
        'duration',
        'active',
        'last_notification_sent_at',
    ];


    protected $casts = [
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    // 🔗 до проекту
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // 🔗 до користувача
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ⏱ Автоматичний розрахунок тривалості при зупинці
    public function stop(): void
    {
        if (!$this->stopped_at) {
            $this->stopped_at = now();
            $this->duration = $this->stopped_at->diffInSeconds($this->started_at);
            $this->save();
        }
    }
}
