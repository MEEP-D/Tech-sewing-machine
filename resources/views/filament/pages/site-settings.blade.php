<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}
                <x-filament::button type="submit">
                    Lưu cấu hình
                </x-filament::button>
            </form>
        </div>
    </div>
</x-filament-panels::page>
