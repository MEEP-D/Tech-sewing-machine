<?php

namespace Tests\Feature;

use App\Models\SiteVisitor;
use App\Services\SiteVisitorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Tests\TestCase;

class SiteVisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_add_active_visitors_to_public_online_baseline(): void
    {
        $service = app(SiteVisitorService::class);

        $service->track($this->trackedRequest('visitor-one'));
        $service->track($this->trackedRequest('visitor-two'));

        $stats = $service->stats();

        $this->assertSame(302, $stats['online_count']);
        $this->assertSame(1200349, $stats['total_visits']);
    }

    public function test_total_visits_count_repeat_page_views_from_same_session(): void
    {
        $service = app(SiteVisitorService::class);
        $request = $this->trackedRequest('repeat-visitor');

        $service->track($request);
        $service->track($request);

        $stats = $service->stats();

        $this->assertSame(301, $stats['online_count']);
        $this->assertSame(1200349, $stats['total_visits']);
        $this->assertSame(2, SiteVisitor::query()->where('session_id', 'repeat-visitor')->value('total_requests'));
    }

    private function trackedRequest(string $sessionId): Request
    {
        $request = Request::create('/', 'GET', server: [
            'HTTP_USER_AGENT' => 'Feature test browser',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $session = new Store('test-session', new ArraySessionHandler(120));
        $session->setId($sessionId);
        $request->setLaravelSession($session);

        return $request;
    }
}
