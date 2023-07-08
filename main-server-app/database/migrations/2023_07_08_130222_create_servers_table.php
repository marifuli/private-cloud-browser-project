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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_id');
            $table->string('server_ip');
            $table->string('status');
            $table->boolean('ready')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamp('destroyed_at')->nullable();
            $table->string('cookie_file')->nullable();
            $table->json('user_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
