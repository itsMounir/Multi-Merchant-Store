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
        Schema::create('goal_market', function (Blueprint $table) {

            $table->foreignId('goal_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->foreignId('market_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->primary(['goal_id','market_id']);
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
