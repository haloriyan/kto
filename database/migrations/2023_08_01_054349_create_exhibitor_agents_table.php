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
        Schema::create('exhibitor_agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exhibitor_id')->unsigned()->index();
            $table->foreign('exhibitor_id')->references('id')->on('exhibitors')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitor_agents');
    }
};
