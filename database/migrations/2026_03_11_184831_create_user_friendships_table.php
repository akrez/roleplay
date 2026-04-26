<?php

use App\Enums\UserFriendshipStatusEnum;
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
        Schema::create('user_friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_from')->constrained('users')->restrictOnDelete();
            $table->foreignId('request_to')->constrained('users')->restrictOnDelete();
            $table->enum('status', UserFriendshipStatusEnum::names());
            $table->timestamps();
            //
            $table->unique(['request_from', 'request_to'], 'unique_friendship_between_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_friendships');
    }
};
