<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_id')->constrained()->onDelete('cascade');
        $table->text('question');
        $table->text('choices')->nullable();
        $table->text('answer');
        $table->text('explanation')->nullable();
        $table->string('type')->default('multiple_choice');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
