<x-filament-widgets::widget>
    <x-filament::section heading="Newsletter & Queue" icon="heroicon-o-paper-airplane">
        <div style="display:grid;gap:.6rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                <div style="border:1px solid #d9e2ef;border-radius:.6rem;padding:.55rem .65rem;">
                    <div style="font-size:.72rem;color:#475569;">Campaigns</div>
                    <div style="font-size:1rem;font-weight:700;">{{ $campaigns }}</div>
                </div>
                <div style="border:1px solid #d9e2ef;border-radius:.6rem;padding:.55rem .65rem;">
                    <div style="font-size:.72rem;color:#475569;">Logs</div>
                    <div style="font-size:1rem;font-weight:700;">{{ $logs }}</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                <div style="border:1px solid #d9e2ef;border-radius:.6rem;padding:.55rem .65rem;">
                    <div style="font-size:.72rem;color:#475569;">Sent / Failed</div>
                    <div style="font-size:1rem;font-weight:700;">{{ $sentLogs }} / {{ $failedLogs }}</div>
                </div>
                <div style="border:1px solid #d9e2ef;border-radius:.6rem;padding:.55rem .65rem;">
                    <div style="font-size:.72rem;color:#475569;">Queued Jobs</div>
                    <div style="font-size:1rem;font-weight:700;">{{ $queuedNewsletterJobs }}</div>
                </div>
            </div>

            <div style="border:1px solid {{ $duePostsWithoutCampaign > 0 ? '#f59e0b' : '#16a34a' }};border-radius:.6rem;padding:.6rem .7rem;background:{{ $duePostsWithoutCampaign > 0 ? '#fffbeb' : '#f0fdf4' }};">
                <div style="font-size:.78rem;font-weight:700;color:#0f172a;">
                    Bài đã đến giờ publish nhưng chưa có campaign: {{ $duePostsWithoutCampaign }}
                </div>
            </div>

            <div style="font-size:.75rem;color:#475569;line-height:1.45;">
                Queue: <strong>{{ $queueConnection }}</strong><br>
                Timezone: <strong>{{ $appTimezone }}</strong><br>
                Lệnh cần chạy trên server:
                <code>php artisan queue:work --queue=default --tries=3</code> và
                <code>php artisan schedule:work</code>
            </div>

            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <a href="{{ url('/admin/newsletter-campaigns') }}" style="text-decoration:none;border:1px solid #d9e2ef;border-radius:.5rem;padding:.35rem .6rem;font-size:.75rem;">Mở Campaigns</a>
                <a href="{{ url('/admin/newsletter-logs') }}" style="text-decoration:none;border:1px solid #d9e2ef;border-radius:.5rem;padding:.35rem .6rem;font-size:.75rem;">Mở Logs</a>
                <a href="{{ url('/admin/newsletter-subscribers') }}" style="text-decoration:none;border:1px solid #d9e2ef;border-radius:.5rem;padding:.35rem .6rem;font-size:.75rem;">Mở Subscribers</a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

