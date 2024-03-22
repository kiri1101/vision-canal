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
        Schema::create('authorization_tokens', function (Blueprint $table) {
            $table->id();
            $table->unique(['app_id', 'app_secret']);
            $table->string('app_id');
            $table->string('app_secret');
            $table->boolean('is_active')->default(false);
            $table->string('device_manufacturer');
            $table->string('device_model');
            $table->string('device_name');
            $table->string('android_os');
            $table->string('android_version');
            $table->string('device_platform');
            $table->string('ip_address');
            $table->string('locale');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorization_tokens');
    }
};
