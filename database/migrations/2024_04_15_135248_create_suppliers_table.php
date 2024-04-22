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
        Schema::create('suppliers', function (Blueprint $table) {

            $table->foreignId('supplier_category_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('store_name');
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->float('min_bill_price');
            $table->integer('min_selling_quantity');
            $table->string('delivery_duration')->nullable();

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
