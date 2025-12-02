<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageWaitingMessage extends Model
{
    protected $fillable = [
        'waiting_log_id',
        'from',
        'message',
    ];

    public function waiting()
    {
        return $this->belongsTo(ProjectStageWaitingLog::class, 'waiting_log_id');
    }
}

