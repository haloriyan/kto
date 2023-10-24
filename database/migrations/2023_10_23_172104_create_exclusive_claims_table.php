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
        Schema::create('exclusive_claims', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('visitor_id')->unsigned()->index();
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->boolean('is_accepted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exclusive_claims');
    }
};
