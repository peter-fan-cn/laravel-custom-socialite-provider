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
        Schema::create('oauth_providers', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50);
            $table->string('sub', 50);
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name', 50);
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('avatar', 50)->nullable();
            $table->string('scope', 50)->nullable();
            $table->timestamps();

            $table->index(['sub','provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_providers');
    }
};
