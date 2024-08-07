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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('market_category_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('store_name');
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->string('representator_code')->default('');
            $table->boolean('is_subscribed')->default(false);
            $table->date('subscription_expires_at');
            $table->enum('status', ['نشط', 'محظور', 'غير نشط'])->default('غير نشط');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
