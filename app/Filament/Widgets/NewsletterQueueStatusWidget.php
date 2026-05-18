<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterLog;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Widget;

class NewsletterQueueStatusWidget extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.newsletter-queue-status-widget';

    protected int|string|array $columnSpan = 1;

    protected function getViewData(): array
    {
        $campaigns = NewsletterCampaign::query()->count();
        $logs = NewsletterLog::query()->count();
        $sentLogs = NewsletterLog::query()->where('status', 'sent')->count();
        $failedLogs = NewsletterLog::query()->where('status', 'failed')->count();

        $duePostsWithoutCampaign = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereDoesntHave('newsletterCampaign')
            ->count();

        $queuedNewsletterJobs = DB::table('jobs')
            ->where('payload', 'like', '%SendNewsletterPostJob%')
            ->count();

        return [
            'campaigns' => $campaigns,
            'logs' => $logs,
            'sentLogs' => $sentLogs,
            'failedLogs' => $failedLogs,
            'duePostsWithoutCampaign' => $duePostsWithoutCampaign,
            'queuedNewsletterJobs' => $queuedNewsletterJobs,
            'queueConnection' => (string) config('queue.default'),
            'appTimezone' => (string) config('app.timezone'),
        ];
    }
}

