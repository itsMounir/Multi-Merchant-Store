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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_category_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('city_id')
                ->constrained();

            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('store_name');
            $table->string('phone_number')->unique();
            $table->string('password');
           // $table->float('min_bill_price');


            // this value is the minimum number of products that should be demanded
            // like if min_selling_quantity is equal to 5  the bill should contains at least 5 different products
            $table->integer('min_selling_quantity');
            $table->string('delivery_duration')->default('التوصيل خلال يومين');

            $table->enum('status', ['نشط', 'محظور', 'غير نشط'])->default('غير نشط');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
