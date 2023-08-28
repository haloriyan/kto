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
        Schema::create('exhibitors', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id');
            $table->string('name');
            $table->string('slug');
            $table->longText('description');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('icon')->nullable();
            $table->string('cover')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->integer('max_appointment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitors');
    }
};
