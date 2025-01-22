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
        Schema::create('teacher_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('father_name')->nullable();
            $table->integer('marital_status')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('edu_qualification')->nullable();
            $table->string('training_qualification')->nullable();
            $table->string('other_occupation')->nullable();
            $table->string('occupation_details')->nullable();
            $table->enum('class_device', ['laptop', 'desktop', 'not'])->nullable();
            $table->string('is_all_agree')->nullable();
            $table->string('certificate_file')->nullable();
            $table->string('nid_file')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_details');
    }
};
