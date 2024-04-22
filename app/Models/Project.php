<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    /**
     * student() used to define the opposite of the 1-1 relationship between student and project
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * projectsupervisor() defines the opposite of the 1 to many relationship between project supervisor and project
     * @return BelongsTo
     */
    public function projectsupervisor(): BelongsTo
    {
        return $this->belongsTo(ProjectSupervisor::class, 'project_supervisor_id');
    }
}
