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
        Schema::table('visitors', function (Blueprint $table) {
            $table->boolean('has_claim_techno_area')->after('password');
            $table->boolean('has_claim_exclusive_gift')->after('has_claim_techno_area');
            $table->boolean('has_claim_mystery_gift')->after('has_claim_exclusive_gift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            //
        });
    }
};
