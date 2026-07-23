<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_discussions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('course_lesson_id')
                ->constrained('course_lessons')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('lesson_discussions')
                ->cascadeOnDelete();

            $table->text('message');
            $table->boolean('is_answered')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->index(
                ['course_lesson_id', 'parent_id'],
                'lesson_discussions_lesson_parent_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_discussions');
    }
};
