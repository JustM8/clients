<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'status_id',
        'telegram_thread_id',
        'rate',
        'type_id',
        'buffer_hours',
    ];


    // 🔗 Зв’язок із клієнтом (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // 🔗 Зв’язок із статусом
    public function status()
    {
        return $this->belongsTo(ProjectStage::class, 'status_id');
    }

    // 🔗 Зв’язок із таймерами
    public function timers()
    {
        return $this->hasMany(ProjectTimer::class);
    }

    // ⏱ Обчислюємо загальну тривалість
    public function getTotalTimeAttribute(): int
    {
        return $this->timers()->sum('duration');
    }

    public function messages()
    {
        return $this->hasMany(\App\Models\ProjectMessage::class);
    }
    public function getTotalCostAttribute()
    {
        $totalHours = $this->timers()->sum('duration') / 3600;
        return $totalHours * $this->rate;
    }

    public function type()
    {
        return $this->belongsTo(ProjectType::class, 'type_id');
    }
    public function stages()
    {
        return $this->hasMany(ProjectStage::class);
    }
    public function stageItems()
    {
        return $this->hasMany(ProjectStageItem::class)->orderBy('position');
    }
    public function waitingStage()
    {
        return $this->hasOne(ProjectStageWaitingLog::class, 'project_id')
            ->where('status', 'running');
    }
    public function waiting()
    {
        return $this->hasMany(ProjectStageWaitingLog::class);
    }

}
