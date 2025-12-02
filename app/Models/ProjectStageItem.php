<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageItem extends Model
{
    protected $fillable = [
        'project_id',
        'stage_id',
        'start_date',
        'end_date',
        'expected_end_date',
        'spent_seconds',
        'position',
        'custom',
    ];

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'stage_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getDisplayNameAttribute()
    {
        $base = __('admin/projects.stages.' . $this->stage->name);

        $count = self::where('project_id', $this->project_id)
            ->where('stage_id', $this->stage_id)
            ->where('id', '<=', $this->id)
            ->count();

        return $count > 1
            ? "{$base} {$count}"
            : $base;
    }

    public function workLogs()
    {
        return $this->hasMany(ProjectStageTimer::class, 'stage_item_id');
    }

    public function getSpentSecondsAttribute()
    {
        return $this->workLogs()->sum('duration_seconds');
    }

}
