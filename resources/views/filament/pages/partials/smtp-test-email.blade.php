<div class="mt-4 border-t border-gray-200 pt-4 dark:border-gray-700">
    <form wire:submit.prevent="sendTestEmail" class="flex flex-wrap items-end gap-3">
        <div class="min-w-[280px] flex-1">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Email nhận thử</label>
            <input
                type="email"
                wire:model.defer="testEmail"
                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800"
                placeholder="client@example.com"
                required
            >
        </div>
        <x-filament::button type="submit" color="gray">
            Gửi email test
        </x-filament::button>
    </form>
</div>
