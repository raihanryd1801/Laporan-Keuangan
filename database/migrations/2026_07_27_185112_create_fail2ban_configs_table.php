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
        Schema::create('fail2ban_configs', function (Blueprint $table) {
            $table->id();
            $table->string('jail_name')->default('laravel-auth');
            $table->integer('maxretry')->default(3);
            $table->integer('bantime')->default(3600);
            $table->text('ignoreip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fail2ban_configs');
    }
};
