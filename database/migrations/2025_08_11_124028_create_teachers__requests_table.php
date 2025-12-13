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
        Schema::create('teachers__requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('identity_number')->unique();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital_status', ['single', 'married'])->default('single');
            $table->string('education')->nullable();
            $table->string('specialization')->nullable();
            $table->integer('experience_years')->nullable();
            $table->text('previous_work')->nullable();
            $table->unsignedBigInteger('salary_syp')->nullable();
            $table->unsignedBigInteger('salary_usd')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('request_status', ['pending', 'accepted', 'rejected'])
                ->default('pending')
                ->comment('حالة الطلب');
            $table->timestamps();
        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers__requests');
    }
};
