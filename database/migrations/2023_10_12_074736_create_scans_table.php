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
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('visitor_id')->unsigned()->index();
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->bigInteger('seller_id')->unsigned()->index()->nullable();
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
