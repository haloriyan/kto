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
        Schema::table('kmtm_users', function (Blueprint $table) {
            $table->string('website')->nullable()->after('phone');
            $table->string('reference')->nullable()->after('website');
            $table->longText('custom_field')->nullable()->after('from_company');
            $table->string('line_of_business')->nullable()->after('from_company');
            $table->string('company_established')->nullable()->after('line_of_business');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
