<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'expected_duration_days',
        'waiting_time_seconds',
        'stage_key',
        'position',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function waitingLogs()
    {
        return $this->hasMany(ProjectStageWaitingLog::class, 'stage_id');
    }
}
