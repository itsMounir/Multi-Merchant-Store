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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('payment_method_id')
                ->constrained();

            $table->float('total_price');
            $table->float('recieved_price')->default(0);

            $table->enum('status', ['انتظار', 'جديد', 'ملغية', 'تم التوصيل', 'قيد التحضير','رفض الاستلام'])
                ->default('انتظار');
            $table->boolean('has_additional_cost');
            $table->string('market_note')->default('');
            $table->string('rejection_reason')->default('');
            $table->string('delivery_duration')->default('');
            $table->softDeletes();
            $table->timestamps();
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
