<?php

namespace App\Http\Controllers;

use App\Models\ClickLog;
use App\Models\ShortLink;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LinkController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function index(Request $request)
    {
        $links = auth()->user()->shortLinks()
            ->withCount('clickLogs as click_count')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('target_url', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Add public URL to each link
        $links->getCollection()->transform(function ($link) {
            $link->public_url = url('/' . $link->slug);

            return $link;
        });

        return Inertia::render('Links/Index', [
            'links' => $links,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Links/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_url' => ['required', 'url', 'max:2048'],
            'custom_alias' => ['nullable', 'string', 'alpha_dash', 'unique:short_links,slug', 'max:100'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $slug = $validated['custom_alias'] ?? $this->generateUniqueSlug();

        $link = auth()->user()->shortLinks()->create([
            'slug' => $slug,
            'custom_alias' => $validated['custom_alias'] ?? null,
            'target_url' => $validated['target_url'],
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('links.show', $link)
            ->with('success', 'Short link created successfully!');
    }

    public function show(ShortLink $link)
    {
        $this->authorize('view', $link);

        $analytics = $this->analyticsService->getLinkAnalytics($link, 30);

        $link->load('user:id,name,email');
        $link->public_url = url('/' . $link->slug);

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
            'target_url' => ['required', 'url', 'max:2048'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ]);

        $link->update($validated);

        // Clear cache after update
        $this->analyticsService->clearLinkCache($link);

        return redirect()->route('links.show', $link)
            ->with('success', 'Link updated successfully!');
    }

    public function destroy(ShortLink $link)
    {
        $this->authorize('delete', $link);

        // Clear cache before deletion
        $this->analyticsService->clearLinkCache($link);
        $this->analyticsService->clearUserCache(auth()->id());

        $link->delete();

        return redirect()->route('links.index')
            ->with('success', 'Link deleted successfully!');
    }

    public function redirect(string $slug)
    {
        $link = ShortLink::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if ($link->expires_at && $link->expires_at->isPast()) {
            abort(410, 'This link has expired.');
        }

        $this->logClick($link);

        $link->increment('click_count');

        // Clear analytics cache after click
        $this->analyticsService->clearLinkCache($link);

        return redirect($link->target_url);
    }

    /**
     * Log click with detailed analytics.
     */
    protected function logClick(ShortLink $link): void
    {
        $userAgent = request()->userAgent();

        ClickLog::create([
            'short_link_id' => $link->id,
            'ip_address' => request()->ip(),
            'user_agent' => $userAgent,
            'referrer' => request()->header('referer'),
            'device_type' => $this->detectDevice($userAgent),
            'browser_name' => $this->detectBrowser($userAgent),
            'os' => $this->detectOS($userAgent),
            'clicked_at' => now(),
        ]);
    }

    /**
     * Generate unique slug.
     */
    protected function generateUniqueSlug(int $length = 6): string
    {
        do {
            $slug = Str::random($length);
        } while (ShortLink::where('slug', $slug)->exists());

        return $slug;
    }

    /**
     * Detect device type from user agent.
     */
    protected function detectDevice(string $userAgent): string
    {
        if (preg_match('/mobile|android|iphone|ipad/i', $userAgent)) {
            return 'mobile';
        }

        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Detect browser from user agent.
     */
    protected function detectBrowser(string $userAgent): string
    {
        if (preg_match('/Edge/i', $userAgent)) {
            return 'Edge';
        }
        if (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        }
        if (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        }
        if (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        }
        if (preg_match('/Opera|OPR/i', $userAgent)) {
            return 'Opera';
        }

        return 'Unknown';
    }

    /**
     * Detect operating system from user agent.
     */
    protected function detectOS(string $userAgent): string
    {
        if (preg_match('/Windows/i', $userAgent)) {
            return 'Windows';
        }
        if (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
            return 'macOS';
        }
        if (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        }
        if (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        }
        if (preg_match('/iOS|iPhone|iPad/i', $userAgent)) {
            return 'iOS';
        }

        return 'Unknown';
    }
}
