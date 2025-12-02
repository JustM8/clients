<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageTimer extends Model
{
    use HasFactory;

    protected $table = 'project_stage_work_logs';

    protected $fillable = [
        'project_id',
        'stage_item_id',
        'started_at',
        'stopped_at',
        'duration_seconds',
    ];

    protected $dates = [
        'started_at',
        'stopped_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function stageItem()
    {
        return $this->belongsTo(ProjectStageItem::class, 'stage_item_id');
    }
}

