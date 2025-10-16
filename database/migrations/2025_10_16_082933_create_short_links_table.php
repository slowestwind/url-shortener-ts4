<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('slug')->unique(); // short code
            $table->string('custom_alias')->nullable()->unique(); // optional custom alias
            $table->text('target_url');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // categorize links
            $table->json('utm_params')->default('{}'); // UTM tracking parameters
            $table->integer('click_count')->default(0);
            $table->timestamp('scheduled_at')->nullable(); // for future link scheduling
            $table->timestamp('expires_at')->nullable(); // link expiration
            $table->boolean('is_active')->default(true);
            $table->json('qr_settings')->default('{}'); // QR code customization
            $table->string('qr_path')->nullable(); // stored QR code path
            $table->timestamps();
            $table->index('slug');
            $table->index('user_id');
            $table->index('custom_alias');
            $table->fullText(['title', 'description']); // for searching
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
