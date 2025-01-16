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
        Schema::create('active_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('teacher_id')->nullable();
            $table->string('message')->nullable();
            $table->integer('is_active')->default(DEACTIVATE);
            $table->integer('notify_count')->nullable(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_checks');
    }
};
