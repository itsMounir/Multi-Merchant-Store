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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('phone_number')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

        /*Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->float('total_price');
            $table->enum('payement_method',['كاش','بطاقة']);
            $table->enum('status',['مدفوع','غير مدفوع','تم التوصيل']);
            $table->string('discount_code');
            $table->timestamps();
        });*/

                /*Schema::create('bill_product_supplier', function (Blueprint $table) {

            $table->foreignId('bill_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('product_supplier_id')
                ->constrained('product_supplier')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['bill_id','product_supplier_id']);
            $table->timestamps();
        });*/
