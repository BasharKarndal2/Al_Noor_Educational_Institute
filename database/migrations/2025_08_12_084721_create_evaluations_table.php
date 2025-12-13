<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
                Schema::create('evaluations', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->enum('type', [
                        'quiz',
                        'exam',
                        'assignment',
                        'project',
                        'activity',
                        'participation'
                    ]);
                    $table->enum('frequency', ['daily', 'weekly', 'monthly']);
                    $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                    $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
                    $table->foreignId('section_id')->constrained()->onDelete('cascade');
                    $table->date('evaluation_date');
                    $table->text('description')->nullable();
                    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
