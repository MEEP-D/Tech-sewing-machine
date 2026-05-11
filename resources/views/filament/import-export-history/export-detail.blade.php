@php
    $failedRowsCount = $record->getFailedRowsCount();
    $status = blank($record->completed_at)
        ? 'Đang xử lý'
        : ($fileAvailable ? ($failedRowsCount > 0 ? 'Hoàn tất có lỗi' : 'Hoàn tất') : 'Hoàn tất nhưng thiếu file');
    $directory = $record->getFileDirectory();
    $xlsxPath = filled($record->file_name) ? $directory . DIRECTORY_SEPARATOR . $record->file_name . '.xlsx' : null;
@endphp

<div class="space-y-5">
    <div class="grid gap-3 md:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Trạng thái</p>
            <p class="mt-1 text-sm font-bold text-gray-950 dark:text-gray-100">{{ $status }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Tổng dòng</p>
            <p class="mt-1 text-sm font-bold text-gray-950 dark:text-gray-100">{{ number_format($record->total_rows) }}</p>
        </div>
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 dark:border-green-900/60 dark:bg-green-950/25">
            <p class="text-xs font-semibold uppercase text-green-700 dark:text-green-300">Thành công</p>
            <p class="mt-1 text-sm font-bold text-green-800 dark:text-green-200">{{ number_format($record->successful_rows) }}</p>
        </div>
        <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-900/60 dark:bg-red-950/25">
            <p class="text-xs font-semibold uppercase text-red-700 dark:text-red-300">Dòng lỗi</p>
            <p class="mt-1 text-sm font-bold text-red-800 dark:text-red-200">{{ number_format($failedRowsCount) }}</p>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div>
                <dt class="font-semibold text-gray-500 dark:text-gray-400">File export</dt>
                <dd class="mt-1 break-all text-gray-950 dark:text-gray-100">{{ filled($record->file_name) ? $record->file_name . '.xlsx' : 'Chưa tạo tên file' }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-500 dark:text-gray-400">File còn lưu</dt>
                <dd class="mt-1 text-gray-950 dark:text-gray-100">{{ $fileAvailable ? 'Có thể tải lại' : 'Không tìm thấy file trên storage' }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-500 dark:text-gray-400">Vị trí export</dt>
                <dd class="mt-1 break-all text-gray-950 dark:text-gray-100">{{ $record->file_disk }}:{{ $xlsxPath ?? $directory }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-500 dark:text-gray-400">Người export</dt>
                <dd class="mt-1 text-gray-950 dark:text-gray-100">{{ $record->user?->email ?? $record->user?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-500 dark:text-gray-400">Bắt đầu</dt>
                <dd class="mt-1 text-gray-950 dark:text-gray-100">{{ $record->created_at?->format('d/m/Y H:i:s') }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-500 dark:text-gray-400">Hoàn tất</dt>
                <dd class="mt-1 text-gray-950 dark:text-gray-100">{{ $record->completed_at?->format('d/m/Y H:i:s') ?? 'Chưa hoàn tất' }}</dd>
            </div>
        </dl>
    </div>

    @if (! $fileAvailable)
        <p class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/25 dark:text-red-200">
            Export đã có lịch sử trong database nhưng file không còn trong storage. Vị trí mong đợi là
            <span class="font-mono">{{ $record->file_disk }}:{{ $xlsxPath ?? $directory }}</span>.
        </p>
    @elseif ($failedRowsCount > 0)
        <p class="rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-sm text-yellow-800 dark:border-yellow-900/60 dark:bg-yellow-950/25 dark:text-yellow-200">
            Export hoàn tất nhưng số dòng thành công nhỏ hơn tổng dòng. Filament không lưu chi tiết lỗi theo từng dòng export; hãy kiểm tra log hệ thống nếu cần nguyên nhân cấp job.
        </p>
    @else
        <p class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800 dark:border-green-900/60 dark:bg-green-950/25 dark:text-green-200">
            File export còn lưu và có thể tải lại từ nút “Tải Excel”.
        </p>
    @endif

    @if ($failedJobs->isNotEmpty())
        <div>
            <h3 class="text-sm font-bold text-gray-950 dark:text-gray-100">Lỗi job hệ thống</h3>

            <div class="mt-3 space-y-3">
                @foreach ($failedJobs as $failedJob)
                    <div class="rounded-lg border border-red-200 bg-red-50/70 p-3 dark:border-red-900/60 dark:bg-red-950/20">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="text-sm font-bold text-red-800 dark:text-red-200">Queue: {{ $failedJob->queue }}</p>
                            <p class="text-xs text-red-700 dark:text-red-300">{{ $failedJob->failed_at }}</p>
                        </div>

                        <pre class="mt-3 max-h-56 overflow-auto rounded-md bg-white p-3 text-xs text-gray-800 dark:bg-gray-950 dark:text-gray-200">{{ \Illuminate\Support\Str::limit($failedJob->exception, 2000) }}</pre>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
