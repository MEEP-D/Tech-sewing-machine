<?php

namespace App\Services;

use App\Models\SiteVisitor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SiteVisitorService
{
    private const ONLINE_WINDOW_MINUTES = 5;
    private const DEFAULT_ONLINE_COUNT = 300;
    private const DEFAULT_TOTAL_VISITS = 1200347;

    private static ?bool $siteVisitorsTableExists = null;

    private static ?array $stats = null;

    public function track(Request $request): void
    {
        if (! $this->shouldTrack($request) || ! $this->siteVisitorsTableExists()) {
            return;
        }

        $session = $request->session();

        if (! $session->isStarted()) {
            $session->start();
        }

        $sessionId = (string) $session->getId();

        if ($sessionId === '') {
            return;
        }

        $now = now();
        $attributes = [
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'last_seen_at' => $now,
        ];

        $visitor = SiteVisitor::query()->firstWhere('session_id', $sessionId);

        if ($visitor) {
            $visitor->forceFill([
                ...$attributes,
                'total_requests' => (int) $visitor->total_requests + 1,
            ])->save();

            self::$stats = null;

            return;
        }

        try {
            SiteVisitor::query()->create([
                'session_id' => $sessionId,
                'first_seen_at' => $now,
                'total_requests' => 1,
                ...$attributes,
            ]);
        } catch (QueryException) {
            SiteVisitor::query()
                ->where('session_id', $sessionId)
                ->update([
                    ...$attributes,
                    'total_requests' => DB::raw('total_requests + 1'),
                ]);
        }

        self::$stats = null;
    }

    public function stats(): array
    {
        if (self::$stats !== null) {
            return self::$stats;
        }

        if (! $this->siteVisitorsTableExists()) {
            return self::$stats = [
                'online_count' => self::DEFAULT_ONLINE_COUNT,
                'total_visits' => self::DEFAULT_TOTAL_VISITS,
            ];
        }

        $onlineThreshold = now()->subMinutes(self::ONLINE_WINDOW_MINUTES);
        $onlineCount = SiteVisitor::query()
            ->where('last_seen_at', '>=', $onlineThreshold)
            ->count();
        $totalVisits = (int) SiteVisitor::query()->sum('total_requests');

        return self::$stats = [
            'online_count' => self::DEFAULT_ONLINE_COUNT + $onlineCount,
            'total_visits' => self::DEFAULT_TOTAL_VISITS + $totalVisits,
        ];
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return false;
        }

        if (in_array((string) optional($request->route())->getName(), ['sitemap', 'robots'], true)) {
            return false;
        }

        return true;
    }

    private function siteVisitorsTableExists(): bool
    {
        return self::$siteVisitorsTableExists ??= Schema::hasTable('site_visitors');
    }
}
