<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_name',
        'coverage_segment',
        'description',
        'start_date',
        'end_date',
        'created_by',
        'project_status'

    ];


    // ✅ Define the relationship between Project and Task
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id'); // Ensure project_id is the correct foreign key
    }
}
