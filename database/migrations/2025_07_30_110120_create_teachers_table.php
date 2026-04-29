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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id(); // معرف المعلم
            $table->string('full_name'); // الاسم الكامل
            $table->string('national_id')->nullable(); // رقم الهوية
            $table->date('birth_date'); // تاريخ الميلاد
            $table->enum('gender', ['male', 'female'])->default('male'); // الجنس
            $table->enum('marital', ['married', 'single'])->default('single');
            $table->string('specialization')->nullable(); // التخصص
            $table->integer('experience')->default(0); // سنوات الخبرة
            $table->string('phone')->nullable();// رقم الهاتف
            $table->string('email')->unique(); // البريد الإلكتروني
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active'); // الحالة
            $table->date('hire_date'); // تاريخ التعيين
            $table->text('address')->nullable(); // العنوان
            $table->string('image_path')->nullable();
            $table->string('notes')->nullable();  // ملاحظات إضافية
            // مسار صورة المعلم
            $table->timestamps(); // تاريخ الإنشاء والتحديث
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
