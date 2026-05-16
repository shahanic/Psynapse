<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->text('template');
            $table->json('variables')->nullable();
            $table->json('answer_formula')->nullable();
            $table->string('type')->default('multiple_choice');
            $table->string('difficulty')->default('medium');
            $table->string('exam_type')->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['topic_id', 'difficulty', 'exam_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_templates');
    }
};
