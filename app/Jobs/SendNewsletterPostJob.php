<?php

namespace App\Jobs;

use App\Mail\NewsletterPostMail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterLog;
use App\Models\NewsletterSubscriber;
use App\Services\DynamicMailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $campaignId, public int $subscriberId)
    {
    }

    public function handle(): void
    {
        $campaign = NewsletterCampaign::query()->with('post')->find($this->campaignId);
        $subscriber = NewsletterSubscriber::query()->find($this->subscriberId);

        if (! $campaign || ! $subscriber || $subscriber->status !== NewsletterSubscriber::STATUS_ACTIVE) {
            return;
        }

        $log = NewsletterLog::query()->firstOrCreate(
            ['campaign_id' => $campaign->id, 'subscriber_id' => $subscriber->id],
            ['status' => 'queued']
        );

        if ($log->status === 'sent') {
            return;
        }

        try {
            $unsubscribeUrl = route('newsletter.unsubscribe', ['subscriber' => $subscriber->id, 'hash' => sha1($subscriber->email)]);
            app(DynamicMailConfigService::class)->apply();
            Mail::to($subscriber->email)->send(new NewsletterPostMail($campaign, $subscriber, $unsubscribeUrl));

            $log->update([
                'status' => 'sent',
                'error_message' => null,
                'sent_at' => now(),
            ]);

            if (! $campaign->sent_at) {
                $campaign->update(['sent_at' => now()]);
            }
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
