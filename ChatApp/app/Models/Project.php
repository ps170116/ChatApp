<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $fillable = ['name'];

    public function tasks() {
        return $this->hasMany(Task::class);
    }
    public function participants()
    {
      return $this->belongsToMany(User::class, 'project_participants');
    }
}
