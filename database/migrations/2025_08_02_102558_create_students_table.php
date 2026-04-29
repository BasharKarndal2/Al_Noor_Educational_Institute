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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم الطالب');
            $table->string('email')->unique()->comment('البريد الإلكتروني للطالب');
            $table->string('phone')->nullable()->comment('رقم الهاتف للطالب');
            $table->date('date_of_birth')->nullable()->comment('تاريخ ميلاد الطالب');
            $table->text('address')->nullable(); // العنوان
            $table->enum('status', ['active', 'inactive',])->default('active'); // الحالة
            $table->enum('gender', ['male', 'female']); // الجنس
            $table->string('national_id')->nullable();// رقم الهوية
            $table->string('image_path')->nullable(); // مسار صورة الطالب
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // معرف المستخدم المرتبط بالطالب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
