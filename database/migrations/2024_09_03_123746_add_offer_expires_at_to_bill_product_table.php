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
        Schema::table('bill_product', function (Blueprint $table) {
            $table->date('offer_expires_at')->default('9999-1-1')->after('max_offer_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_product', function (Blueprint $table) {
            $table->dropColumn('offer_expires_at');
        });
    }
};
