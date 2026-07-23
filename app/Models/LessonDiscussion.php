<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonDiscussion extends Model
{
    protected $fillable = [
        'user_id',
        'course_lesson_id',
        'parent_id',
        'message',
        'is_answered',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'is_answered' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(
            CourseLesson::class,
            'course_lesson_id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    public function replies(): HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        )->oldest('id');
    }
}
