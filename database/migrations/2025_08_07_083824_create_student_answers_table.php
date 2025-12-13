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
       
            Schema::create('student_answers', function (Blueprint $table) {
                $table->id();

                $table->foreignId('student__requast_id')->constrained('student__requeasts')->onDelete('cascade');
                $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
                $table->enum('selected_option', ['a', 'b', 'c', 'd']);
                $table->boolean('is_correct')->nullable();

                $table->timestamps();
            });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
