<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <form wire:submit.prevent="save" class="space-y-6">
                {{ $this->form }}
                <div class="flex flex-wrap items-end gap-3">
                    <x-filament::button type="button" wire:click="save">
                        Luu cau hinh
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
