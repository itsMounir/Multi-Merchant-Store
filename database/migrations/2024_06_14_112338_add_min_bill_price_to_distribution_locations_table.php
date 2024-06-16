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
        Schema::table('distribution_locations', function (Blueprint $table) {
            $table->float('min_bill_price')->after('to_city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_locations', function (Blueprint $table) {
            $table->dropColumn('min_bill_price');
        });
    }
};
