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

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('store_name');
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->string('city');
            $table->string('street');
            $table->string('representator_code');
            $table->string('is_subscriped');
            $table->enum('status',['نشط','محظور','غير نشط'])->default('غير نشط');
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
