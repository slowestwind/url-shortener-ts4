<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('profile_slug')->unique(); // public profile URL: ts4.in/@slug
            $table->string('display_name')->nullable();
            $table->string('website_url')->nullable();
            $table->json('social_links')->default('{}'); // twitter, instagram, linkedin, etc.
            $table->json('theme_settings')->default('{}'); // bio page theme customization
            $table->boolean('show_analytics')->default(false); // allow public to see analytics
            $table->integer('profile_views')->default(0);
            $table->timestamps();
            $table->index('profile_slug');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
