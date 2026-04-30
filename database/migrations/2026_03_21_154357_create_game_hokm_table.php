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
        Schema::create('game_hokm', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('owner_id')->constrained('users');
            //
            $table->foreignId('player_1_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->integer('player_1_token', false, true)->nullable();
            $table->string('player_1_quote', 64)->nullable();
            //
            $table->foreignId('player_2_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->integer('player_2_token', false, true)->nullable();
            $table->string('player_2_quote', 64)->nullable();
            //
            $table->foreignId('player_3_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->integer('player_3_token', false, true)->nullable();
            $table->string('player_3_quote', 64)->nullable();
            //
            $table->foreignId('player_4_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->integer('player_4_token', false, true)->nullable();
            $table->string('player_4_quote', 64)->nullable();
            //
            $table->unsignedBigInteger('finished_in')->nullable();
            //
            $table->json('data')->nullable();
            //
            $table->unsignedBigInteger('modified_in')->nullable();
            //
            $table->timestamps();
            //
            $table->index(['id', 'player_1']);
            $table->index(['id', 'player_2']);
            $table->index(['id', 'player_3']);
            $table->index(['id', 'player_4']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_hokm');
    }
};
