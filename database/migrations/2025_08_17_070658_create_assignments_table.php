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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();               // عنوان الواجب
            $table->text('description')->nullable();           // تفاصيل إضافية
            $table->unsignedBigInteger('subject_id');          // المادة
            $table->unsignedBigInteger('section_id');          // الشعبة
            $table->unsignedBigInteger('teacher_id');          // المعلم
            $table->dateTime('due_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active'); // الحالة
            $table->string('file_path')->nullable();

            // العلاقات مع onDelete('cascade')
            $table->foreign('subject_id')
                ->references('id')->on('subjects')
                ->onDelete('cascade');

            $table->foreign('section_id')
                ->references('id')->on('sections')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')->on('teachers')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
