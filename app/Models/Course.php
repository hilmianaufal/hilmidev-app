<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'subtitle', 'description', 'thumbnail', 'level',
        'technology', 'estimated_minutes', 'is_published', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'estimated_minutes' => 'integer',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order')->orderBy('id');
    }

    public function lessons()
    {
        return $this->hasManyThrough(
            CourseLesson::class,
            CourseModule::class,
            'course_id',
            'course_module_id'
        );
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getFormattedDurationAttribute(): string
    {
        $hours = intdiv($this->estimated_minutes, 60);
        $minutes = $this->estimated_minutes % 60;

        return $hours > 0
            ? $hours . ' jam' . ($minutes ? ' ' . $minutes . ' menit' : '')
            : $minutes . ' menit';
    }
}
