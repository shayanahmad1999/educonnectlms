<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function students()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // ➕ submissions through assignments
    public function submissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Submission::class,   // final model
            Assignment::class,   // through model
            'course_id',         // FK on assignments
            'assignment_id',     // FK on submissions
            'id',                // PK on courses
            'id'                 // PK on assignments
        );
    }

    // ---------- scopes -----------------------------------------------------

    /**
     * Scope: order by submission count over the last $days.
     */
    public function scopeMostActive($query, int $days = 30)
    {
        return $query->withCount([
            'submissions as submission_count' => fn($q) =>
            $q->where('submitted_at', '>=', now()->subDays($days))
        ])
            ->orderByDesc('submission_count');
    }
}
