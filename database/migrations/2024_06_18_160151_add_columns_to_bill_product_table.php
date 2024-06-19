<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bill_product', function (Blueprint $table) {
            $table->float('buying_price')->after('quantity');
            $table->integer('max_selling_quantity')->after('buying_price');
            $table->boolean('has_offer')->default(false)->after('max_selling_quantity');
            $table->float('offer_buying_price')->default(0)->after('has_offer');
            $table->integer('max_offer_quantity')->default(0)->after('offer_buying_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_product', function (Blueprint $table) {
            $table->dropColumn([
                'buying_price',
                'max_selling_quantity',
                'has_offer',
                'offer_buying_price',
                'max_offer_quantity'
            ]);
        });
    }
};
