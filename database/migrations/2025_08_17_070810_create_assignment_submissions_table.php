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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id'); // الواجب
            $table->unsignedBigInteger('student_id');    // الطالب
            $table->string('submitted_file')->nullable(); // لو رفع ملف
            $table->text('submitted_text')->nullable();   // لو كتب نص
            $table->integer('grade')->nullable();         // علامة الواجب (إذا بدك تربطها مع التقييم لاحقاً)
            $table->text('feedback')->nullable();         // ملاحظات المعلم
            $table->enum('status', ['corrected', 'incorrected'])->default('incorrected'); // الحالة


            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
