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
    /* Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('store_name');
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->string('discount_code')->nullable();
            $table->string('discount_by_code')->nullable();

            $table->enum('type',['مورد','شركة']);
            $table->enum('status',['نشط','محظور','غير نشط'])->default('غير نشط');
            $table->timestamps();
        });*/


       /* Schema::create('distributin_locations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('from_site');
            $table->string('to_site');
            $table->timestamps();
        });*/

       /* Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->morphs('imageable');
            $table->timestamps();
        });*/

        /* Schema::create('product_suppliers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

                $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->float('price')->nullable();
            $table->float('price_after_sales')->nullable();
            $table->timestamps();
        }); */

        /**Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('payement_method_id')
                ->constrained();

            $table->float('total_price');
            $table->enum('status', ['مدفوع', 'غير مدفوع', 'تم التوصيل','قيد التحضير'])
            ->default('غير مدفوع');
            $table->string('discount_code');
            $table->softDeletes();
            $table->timestamps();
        }); */

        /**Schema::create('bill_product', function (Blueprint $table) {

            $table->foreignId('bill_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['bill_id','product_id']);
            $table->integer('quantity');
            $table->timestamps();
        }); */

