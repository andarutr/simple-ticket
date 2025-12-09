<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // [] Ubah bila ada perubahan atau penambahan kolom
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'priority',
        'deadline',
        'status'
    ];

    // One to Many Dengan Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id','id');
    }
}
