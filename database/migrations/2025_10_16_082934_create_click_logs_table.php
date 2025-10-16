<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('click_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_link_id')->constrained('short_links')->cascadeOnDelete();
            $table->ipAddress()->nullable(); // visitor IP
            $table->string('user_agent')->nullable(); // visitor's browser info
            $table->string('referrer')->nullable(); // where click came from
            $table->string('country')->nullable(); // geo-location (GeoIP)
            $table->string('city')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser_name')->nullable();
            $table->string('os')->nullable(); // Windows, macOS, Linux, iOS, Android
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('clicked_at');
            $table->index('short_link_id');
            $table->index('country');
            $table->index('clicked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_logs');
    }
};
