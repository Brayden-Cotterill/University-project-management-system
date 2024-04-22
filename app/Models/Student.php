<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $hidden =
        [
            'student_user_name'
        ];

    /**
     * Identifies the 1-1 relationship between user and student
     * table associated with model is assumed to be id
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * defines the 1-1 relationship between a given student and their project
     * @return HasOne
     */
    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }


}
