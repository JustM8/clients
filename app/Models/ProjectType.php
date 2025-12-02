<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    protected $fillable = ['name'];

    public function projects()
    {
        return $this->hasMany(Project::class, 'type_id');
    }
}
