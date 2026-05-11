<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}
            <x-filament::button type="submit" color="primary">Lưu SEO</x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
