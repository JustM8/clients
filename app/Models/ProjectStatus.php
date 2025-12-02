<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'status_id');
    }
}
