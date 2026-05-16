<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('questions');
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('question_templates')->onDelete('set null');
            $table->foreignId('exam_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type')->default('multiple_choice');
            $table->string('difficulty')->default('medium');
            $table->string('exam_type')->default('general');
            $table->text('question');
            $table->json('choices')->nullable();
            $table->text('answer');
            $table->text('explanation')->nullable();
            $table->json('meta')->nullable();
            $table->string('hash')->unique()->nullable();
            $table->timestamps();

            $table->index(['topic_id', 'difficulty', 'exam_type', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
