<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show($profileSlug)
    {
        $profile = \App\Models\Profile::where('profile_slug', $profileSlug)
            ->firstOrFail();

        $user = $profile->user;

        if (!$user->is_active) {
            abort(404);
        }

        $links = $user->shortLinks()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $profile->increment('profile_views');

        return Inertia::render('Public/Profile', [
            'profile' => $profile,
            'links' => $links,
        ]);
    }

    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'profile' => auth()->user()->profile,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'nullable|string|max:500',
            'display_name' => 'nullable|string|max:100',
            'website_url' => 'nullable|url',
            'social_links' => 'nullable|array',
            'theme_settings' => 'nullable|array',
        ]);

        auth()->user()->profile()->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
