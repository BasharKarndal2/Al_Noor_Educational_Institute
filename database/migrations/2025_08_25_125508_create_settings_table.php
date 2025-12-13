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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
    
           
            $table->string('location')->nullable();      // الموقع
            $table->string('whatsapp', 20)->nullable();    // الهاتف الأساسي
            $table->string('phone2', 20)->nullable();  // الهاتف الثانوي / واتساب
            $table->string('telegram')->nullable();      // رابط تلجرام
            $table->string('email')->nullable();         // البريد الإلكتروني
            $table->string('facebook')->nullable();      // فيسبوك
            $table->string('instagram')->nullable();     // انستغرام
            $table->text('working_hours')->nullable();   // ساعات العمل
      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
