<?php

namespace App\Services;

use App\Models\ClickLog;
use App\Models\ShortLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get comprehensive analytics for a link.
     */
    public function getLinkAnalytics(ShortLink $link, int $days = 30): array
    {
        $cacheKey = "analytics:link:{$link->id}:days:{$days}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($link, $days) {
            $dateFrom = now()->subDays($days);

            return [
                'total_clicks' => $link->clickLogs()->count(),
                'unique_ips' => $link->clickLogs()->distinct('ip_address')->count('ip_address'),
                'today_clicks' => $link->clickLogs()->whereDate('clicked_at', today())->count(),
                'week_clicks' => $link->clickLogs()->where('clicked_at', '>=', now()->subWeek())->count(),
                'month_clicks' => $link->clickLogs()->where('clicked_at', '>=', now()->subMonth())->count(),
                'clicks_by_date' => $this->getClicksByDate($link, $dateFrom),
                'clicks_by_country' => $this->getClicksByCountry($link),
                'clicks_by_device' => $this->getClicksByDevice($link),
                'clicks_by_browser' => $this->getClicksByBrowser($link),
                'clicks_by_os' => $this->getClicksByOS($link),
                'top_referrers' => $this->getTopReferrers($link),
                'recent_clicks' => $this->getRecentClicks($link, 20),
            ];
        });
    }

    /**
     * Get clicks grouped by date.
     */
    protected function getClicksByDate(ShortLink $link, $dateFrom): array
    {
        return $link->clickLogs()
            ->where('clicked_at', '>=', $dateFrom)
            ->select(
                DB::raw('DATE(clicked_at) as date'),
                DB::raw('COUNT(*) as clicks')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(fn ($item) => [
                'date' => $item->date,
                'clicks' => $item->clicks,
            ])
            ->toArray();
    }

    /**
     * Get clicks grouped by country.
     */
    protected function getClicksByCountry(ShortLink $link): array
    {
        return $link->clickLogs()
            ->select('country', DB::raw('COUNT(*) as clicks'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get clicks grouped by device type.
     */
    protected function getClicksByDevice(ShortLink $link): array
    {
        return $link->clickLogs()
            ->select('device_type', DB::raw('COUNT(*) as clicks'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderByDesc('clicks')
            ->get()
            ->toArray();
    }

    /**
     * Get clicks grouped by browser.
     */
    protected function getClicksByBrowser(ShortLink $link): array
    {
        return $link->clickLogs()
            ->select('browser_name', DB::raw('COUNT(*) as clicks'))
            ->whereNotNull('browser_name')
            ->groupBy('browser_name')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get clicks grouped by operating system.
     */
    protected function getClicksByOS(ShortLink $link): array
    {
        return $link->clickLogs()
            ->select('os', DB::raw('COUNT(*) as clicks'))
            ->whereNotNull('os')
            ->groupBy('os')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get top referrers.
     */
    protected function getTopReferrers(ShortLink $link): array
    {
        return $link->clickLogs()
            ->select('referrer', DB::raw('COUNT(*) as clicks'))
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'referrer' => $this->formatReferrer($item->referrer),
                'full_referrer' => $item->referrer,
                'clicks' => $item->clicks,
            ])
            ->toArray();
    }

    /**
     * Get recent clicks.
     */
    protected function getRecentClicks(ShortLink $link, int $limit = 10): array
    {
        return $link->clickLogs()
            ->latest('clicked_at')
            ->limit($limit)
            ->get()
            ->map(fn ($click) => [
                'id' => $click->id,
                'ip_address' => $this->maskIP($click->ip_address),
                'country' => $click->country ?? 'Unknown',
                'city' => $click->city,
                'device_type' => $click->device_type ?? 'Unknown',
                'browser_name' => $click->browser_name ?? 'Unknown',
                'os' => $click->os ?? 'Unknown',
                'referrer' => $click->referrer ?: 'Direct',
                'clicked_at' => $click->clicked_at->diffForHumans(),
            ])
            ->toArray();
    }

    /**
     * Format referrer URL for display.
     */
    protected function formatReferrer(string $referrer): string
    {
        $parsed = parse_url($referrer);

        return $parsed['host'] ?? $referrer;
    }

    /**
     * Mask IP address for privacy (GDPR compliant).
     */
    protected function maskIP(?string $ip): string
    {
        if (! $ip) {
            return 'Unknown';
        }

        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            $parts[3] = 'xxx';

            return implode('.', $parts);
        }

        // For IPv6
        if (str_contains($ip, ':')) {
            $parts = explode(':', $ip);
            $parts[count($parts) - 1] = 'xxxx';

            return implode(':', $parts);
        }

        return $ip;
    }

    /**
     * Get dashboard statistics for user.
     */
    public function getUserStats(int $userId): array
    {
        $cacheKey = "stats:user:{$userId}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($userId) {
            $totalClicks = ClickLog::whereHas('shortLink', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count();

            $todayClicks = ClickLog::whereHas('shortLink', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereDate('clicked_at', today())->count();

            $weekClicks = ClickLog::whereHas('shortLink', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereBetween('clicked_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

            $monthClicks = ClickLog::whereHas('shortLink', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereMonth('clicked_at', now()->month)->count();

            return [
                'total_clicks' => $totalClicks,
                'today_clicks' => $todayClicks,
                'week_clicks' => $weekClicks,
                'month_clicks' => $monthClicks,
            ];
        });
    }

    /**
     * Clear analytics cache for a link.
     */
    public function clearLinkCache(ShortLink $link): void
    {
        Cache::forget("analytics:link:{$link->id}:days:30");
        Cache::forget("analytics:link:{$link->id}:days:7");
    }

    /**
     * Clear user stats cache.
     */
    public function clearUserCache(int $userId): void
    {
        Cache::forget("stats:user:{$userId}");
    }
}
