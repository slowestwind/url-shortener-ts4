<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\ClickLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $links = auth()->user()->shortLinks()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Links/Index', [
            'links' => $links,
        ]);
    }

    public function create()
    {
        return Inertia::render('Links/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_url' => 'required|url',
            'custom_alias' => 'nullable|string|unique:short_links',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $slug = $validated['custom_alias'] ?? Str::random(6);

        $link = auth()->user()->shortLinks()->create([
            'slug' => $slug,
            'custom_alias' => $validated['custom_alias'],
            'target_url' => $validated['target_url'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'expires_at' => $validated['expires_at'],
            'is_active' => true,
        ]);

        return redirect()->route('links.show', $link)
            ->with('success', 'Short link created successfully!');
    }

    public function show(ShortLink $link)
    {
        $this->authorize('view', $link);

        $analytics = [
            'total_clicks' => $link->clickLogs()->count(),
            'today_clicks' => $link->clickLogs()
                ->whereDate('clicked_at', today())
                ->count(),
            'top_countries' => $link->clickLogs()
                ->selectRaw('country, COUNT(*) as count')
                ->groupBy('country')
                ->orderByDesc('count')
                ->limit(5)
                ->get(),
            'recent_clicks' => $link->clickLogs()
                ->orderBy('clicked_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        return Inertia::render('Links/Show', [
            'link' => $link,
            'analytics' => $analytics,
        ]);
    }

    public function edit(ShortLink $link)
    {
        $this->authorize('update', $link);
        return Inertia::render('Links/Edit', ['link' => $link]);
    }

    public function update(Request $request, ShortLink $link)
    {
        $this->authorize('update', $link);

        $validated = $request->validate([
            'target_url' => 'required|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $link->update($validated);

        return redirect()->route('links.show', $link)
            ->with('success', 'Link updated successfully!');
    }

    public function destroy(ShortLink $link)
    {
        $this->authorize('delete', $link);
        $link->delete();

        return redirect()->route('links.index')
            ->with('success', 'Link deleted successfully!');
    }

    public function redirect($slug)
    {
        $link = ShortLink::where('slug', $slug)
            ->orWhere('custom_alias', $slug)
            ->firstOrFail();

        if ($link->isExpired() || !$link->is_active) {
            abort(410); // Gone
        }

        // Log the click
        ClickLog::create([
            'short_link_id' => $link->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->referrer(),
            'country' => $this->getCountryFromIP(request()->ip()),
            'device_type' => $this->getDeviceType(request()->userAgent()),
            'browser_name' => $this->getBrowserName(request()->userAgent()),
            'os' => $this->getOS(request()->userAgent()),
            'clicked_at' => now(),
        ]);

        // Update click count
        $link->increment('click_count');

        return redirect($link->target_url);
    }

    private function getCountryFromIP($ip)
    {
        // TODO: Implement GeoIP lookup using MaxMind or similar
        return 'Unknown';
    }

    private function getDeviceType($userAgent)
    {
        if (preg_match('/mobile|android|iphone|ipod|blackberry|windows phone/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad|android/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function getBrowserName($userAgent)
    {
        if (preg_match('/chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/safari/i', $userAgent)) return 'Safari';
        if (preg_match('/edge/i', $userAgent)) return 'Edge';
        return 'Unknown';
    }

    private function getOS($userAgent)
    {
        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/mac/i', $userAgent)) return 'macOS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) return 'iOS';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        return 'Unknown';
    }
}
