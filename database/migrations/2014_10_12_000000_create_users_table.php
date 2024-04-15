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


/** Schema::create('goals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('starting_date');
            $table->string('expiring_date');
            $table->float('min_price');
            $table->float('discount_price');
            $table->timestamps();
        });*/

        /**Schema::create('goal_market', function (Blueprint $table) {

            $table->foreignId('goal_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->foreignId('market_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->primary(['goal_id','market_id']);
            $table->timestamps();
        }); */
