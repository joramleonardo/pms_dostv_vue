<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'project_id',
        'task_description',
        'task_status',
        'task_due_date',
        'task_category',
        'task_sub_category'
    ];

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'task_id');
    }

}
