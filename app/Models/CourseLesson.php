<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = [
        'course_module_id', 'title', 'slug', 'description', 'video_type',
        'video_path', 'video_url', 'attachment_path', 'duration_minutes',
        'is_preview', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'is_preview' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function embedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $url = trim($this->video_url);

        if ($this->video_type === 'youtube') {
            if (str_contains($url, 'youtube.com/embed/')) {
                return $url;
            }

            if (preg_match('~youtu\.be/([^?&/]+)~', $url, $m)) {
                return 'https://www.youtube.com/embed/' . $m[1];
            }

            if (preg_match('~[?&]v=([^?&/]+)~', $url, $m)) {
                return 'https://www.youtube.com/embed/' . $m[1];
            }
        }

        if ($this->video_type === 'vimeo' && preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }

    public function notes()
    {
        return $this->hasMany(
            \App\Models\LessonNote::class,
            'course_lesson_id'
        );
    }

    public function discussions()
    {
        return $this->hasMany(
            \App\Models\LessonDiscussion::class,
            'course_lesson_id'
        );
    }
}
