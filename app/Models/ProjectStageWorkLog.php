<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageWorkLog extends Model
{
    protected $fillable = [
        'project_id',
        'stage_item_id',
        'started_at',
        'stopped_at',
        'duration_seconds',
    ];

    protected $dates = ['started_at', 'stopped_at'];
}
