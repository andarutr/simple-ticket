<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // [] Ubah bila ada perubahan atau penambahan kolom
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status'
    ];

    // One to Many Dengan Task
    public function task()
    {
        return $this->hasMany(Task::class, 'project_id','id');
    }
}
