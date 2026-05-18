<?php

namespace App\Console\Commands;

use App\Services\NewsletterService;
use Illuminate\Console\Command;

class DispatchDueNewsletterCampaigns extends Command
{
    protected $signature = 'newsletter:dispatch-due';

    protected $description = 'Queue newsletter campaigns for published posts that are due and not yet queued';

    public function handle(NewsletterService $newsletterService): int
    {
        $count = $newsletterService->queueCampaignsForDuePosts();

        $this->info("Queued {$count} due newsletter campaign(s).");

        return self::SUCCESS;
    }
}

