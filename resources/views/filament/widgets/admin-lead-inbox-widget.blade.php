<x-filament-widgets::widget>
    @php($hasUnread = ($newLeadsCount ?? 0) > 0)

    <x-filament::section
        heading="Yêu cầu liên hệ"
        :icon="$hasUnread ? 'heroicon-o-bell-alert' : 'heroicon-o-bell'"
        :icon-color="$hasUnread ? 'danger' : 'gray'"
    >
        @if ($hasUnread)
            <x-slot name="afterHeader">
                <span class="admin-alert-pill">
                    <span></span>
                    {{ $newLeadsCount }} mới
                </span>
            </x-slot>
        @endif

        <div @class([
            'admin-lead-summary',
            'admin-lead-summary-alert' => $hasUnread,
        ])>
            <div>
                <strong>{{ $hasUnread ? 'Có liên hệ mới chưa xử lý' : 'Hộp thư đang ổn định' }}</strong>
                <p>Đang có {{ $newLeadsCount }} yêu cầu mới cần kiểm tra.</p>
            </div>

            <a href="{{ url('/admin/leads') }}">Xem tất cả</a>
        </div>

        <div class="admin-lead-list">
            @forelse ($latestLeads as $lead)
                <a href="{{ url('/admin/leads/' . $lead->id . '/edit') }}" class="admin-lead-row">
                    <span class="admin-lead-avatar">{{ mb_substr($lead->name, 0, 1) }}</span>

                    <span class="admin-lead-main">
                        <span class="admin-lead-name">
                            {{ $lead->name }}

                            @if ($lead->status === 'new')
                                <em>Mới</em>
                            @endif
                        </span>

                        <span class="admin-lead-phone">{{ $lead->phone }}</span>
                    </span>

                    <span class="admin-lead-time">{{ $lead->created_at?->format('d/m H:i') }}</span>
                </a>
            @empty
                <p class="admin-empty-state">Chưa có yêu cầu liên hệ.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
