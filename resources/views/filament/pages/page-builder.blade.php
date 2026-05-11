<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="space-y-6">
            {{ $this->form }}

            <div class="flex flex-wrap gap-2">
                <x-filament::button wire:click="save" color="primary">Lưu builder</x-filament::button>
                <x-filament::button wire:click="cloneNode(0)" color="gray">Clone node đầu tiên</x-filament::button>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <h3 class="mb-3 text-sm font-semibold">Quick actions</h3>
                <p class="text-sm text-gray-500">Dùng actions trong từng node khi mình bổ sung UI controls chi tiết hơn.</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
            <div class="border-b border-gray-200 p-3 text-sm font-semibold dark:border-gray-700">Live Preview</div>
            <iframe title="Page builder preview" class="h-[80vh] w-full" srcdoc="{!! e($this->previewHtml) !!}"></iframe>
        </div>
    </div>
</x-filament-panels::page>
