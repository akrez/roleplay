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
        Schema::create('game_xo', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('owner_id')->constrained('users');
            //
            $table->foreignId('player_x_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('player_x_token')->nullable()->unique();
            $table->string('player_x_name')->nullable();
            //
            $table->foreignId('player_o_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('player_o_token')->nullable()->unique();
            $table->string('player_o_name')->nullable();
            //
            $table->json('winners')->nullable();
            //
            $table->json('data')->nullable();
            //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_xo');
    }
};
