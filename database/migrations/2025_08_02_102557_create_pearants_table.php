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
        Schema::create('pearants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم ولي الأمر');
            $table->string('email')->unique()->comment('البريد الإلكتروني لولي الأمر');
            $table->string('phone')->comment('رقم الهاتف لولي الأمر');
            $table->string('relation')->comment('صلة القرابة بالطالب');
            $table->string('address')->nullable()->comment('عنوان ولي الأمر');
            $table->date('date_of_birth')->nullable()->comment('تاريخ ميلاد ');
            $table->enum('status', ['active', 'inactive',])->default('active');
            $table->string('national_id')->unique(); // رقم الهوية
            $table->string('image_path')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male'); // الجنس
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pearants');
    }
};
