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
     

        Schema::create('student__requeasts', function (Blueprint $table) {
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
            $table->text('password')->nullable(); // كلمة المرور
            $table->enum('request_status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('حالة الطلب');
            $table->foreignId('parent_id')->nullable()->constrained('pearants')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();

           


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student__requeasts');
    }
};
