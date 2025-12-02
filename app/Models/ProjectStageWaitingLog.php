<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageWaitingLog extends Model
{
    protected $fillable = [
        'project_id',
        'stage_id',

        'started_at',
        'started_by_admin',
        'admin_comment',

        'client_stopped_at',
        'client_comment',

        'rejected_by_admin_at',
        'rejected_admin_comment',

        'stopped_at',
        'stopped_by_admin',
        'duration_seconds',

        'status',
        'last_notification_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'client_stopped_at' => 'datetime',
        'rejected_by_admin_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_seconds) return null;

        $h = floor($this->duration_seconds / 3600);
        $m = floor(($this->duration_seconds % 3600) / 60);

        return ($h ? "{$h}г " : "") . "{$m}хв";
    }

    public function stage() {
        return $this->belongsTo(ProjectStage::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function messages()
    {
        return $this->hasMany(ProjectStageWaitingMessage::class, 'waiting_log_id');
    }

}

