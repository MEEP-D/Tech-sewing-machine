<?php

namespace App\Filament\Pages;

use App\Filament\Support\AdminFormValidation as V;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SeoSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';
    protected string $view = 'filament.pages.seo-settings';

    public static function getNavigationLabel(): string
    {
        return self::u('C\\u00e0i \\u0111\\u1eb7t SEO');
    }

    public function getTitle(): string
    {
        return self::u('C\\u1ea5u h\\u00ecnh SEO');
    }

    public array $data = [];

    public function mount(): void
    {
        $defaultOgImage = Setting::getValue('seo_default_og_image', null);

        $this->data = [
            'seo_default_title' => Setting::getValue('seo_default_title', config('app.name')),
            'seo_default_description' => Setting::getValue('seo_default_description', ''),
            'seo_default_og_image_upload' => $this->normalizeUploadFieldState($defaultOgImage),
            'seo_default_og_image' => $this->normalizeUploadInput($defaultOgImage),
            'seo_default_canonical' => Setting::getValue('seo_default_canonical', config('app.url')),
            'seo_organization_name' => Setting::getValue('seo_organization_name', config('app.name')),
            'seo_organization_url' => Setting::getValue('seo_organization_url', config('app.url')),
            'seo_robots_default' => Setting::getValue('seo_robots_default', 'index,follow'),
            'seo_enable_schema' => Setting::getValue('seo_enable_schema', true),
            'seo_enable_og' => Setting::getValue('seo_enable_og', true),
            'seo_products_title' => Setting::getValue('seo_products_title', self::u('T\\u1ea5t c\\u1ea3 s\\u1ea3n ph\\u1ea9m')),
            'seo_products_description' => Setting::getValue('seo_products_description', self::u('Danh s\\u00e1ch m\\u00e1y may c\\u00f4ng nghi\\u1ec7p ch\\u1ea5t l\\u01b0\\u1ee3ng cao')),
            'seo_news_title' => Setting::getValue('seo_news_title', self::u('Tin t\\u1ee9c & S\\u1ef1 ki\\u1ec7n')),
            'seo_news_description' => Setting::getValue('seo_news_description', self::u('C\\u1eadp nh\\u1eadt tin t\\u1ee9c m\\u1edbi nh\\u1ea5t v\\u1ec1 ng\\u00e0nh may m\\u1eb7c v\\u00e0 s\\u1ef1 ki\\u1ec7n c\\u00f4ng ngh\\u1ec7.')),
            'seo_contact_title' => Setting::getValue('seo_contact_title', self::u('Li\\u00ean h\\u1ec7 TechSewing')),
            'seo_contact_description' => Setting::getValue('seo_contact_description', self::u('Nh\\u1eadn t\\u01b0 v\\u1ea5n gi\\u1ea3i ph\\u00e1p m\\u00e1y may c\\u00f4ng nghi\\u1ec7p, b\\u00e1o gi\\u00e1, demo v\\u00e0 h\\u1ed7 tr\\u1ee3 k\\u1ef9 thu\\u1eadt t\\u1eeb TechSewing.')),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            \Filament\Schemas\Components\Tabs::make('Cài đặt SEO')->tabs([
                \Filament\Schemas\Components\Tabs\Tab::make(self::u('M\\u1eb7c \\u0111\\u1ecbnh to\\u00e0n website'))->schema([
                    Grid::make(2)->schema([
                        TextInput::make('seo_default_title')->label(self::u('SEO title m\\u1eb7c \\u0111\\u1ecbnh')),
                        TextInput::make('seo_default_canonical')->label(self::u('Canonical m\\u1eb7c \\u0111\\u1ecbnh')),
                        TextInput::make('seo_default_og_image')
                            ->label(self::u('\\u1ea2nh OG m\\u1eb7c \\u0111\\u1ecbnh'))
                            ->maxLength(500)
                            ->helperText(self::u('Nh\\u1eadp \\u0111\\u01b0\\u1eddng d\\u1eabn trong storage, assets/... ho\\u1eb7c URL \\u0111\\u1ea7y \\u0111\\u1ee7.')),
                        FileUpload::make('seo_default_og_image_upload')
                            ->label(self::u('T\\u1ea3i \\u1ea3nh OG m\\u1eb7c \\u0111\\u1ecbnh'))
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('seo')
                            ->afterStateHydrated(function ($component, $state): void {
                                if (is_array($state)) {
                                    $component->state($state);
                                    return;
                                }

                                $component->state(filled($state) ? [$state] : []);
                            })
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                        TextInput::make('seo_organization_name')->label(self::u('T\\u00ean t\\u1ed5 ch\\u1ee9c')),
                        TextInput::make('seo_organization_url')->label(self::u('URL t\\u1ed5 ch\\u1ee9c')),
                        TextInput::make('seo_robots_default')->label(self::u('Robots m\\u1eb7c \\u0111\\u1ecbnh')),
                        Checkbox::make('seo_enable_schema')->label(self::u('B\\u1eadt JSON-LD schema'))->default(true),
                        Checkbox::make('seo_enable_og')->label(self::u('B\\u1eadt Open Graph'))->default(true),
                    ]),
                    Textarea::make('seo_default_description')->label(self::u('SEO description m\\u1eb7c \\u0111\\u1ecbnh')),
                ]),
                \Filament\Schemas\Components\Tabs\Tab::make(self::u('Trang T\\u0129nh (Static Pages)'))->schema([
                    \Filament\Schemas\Components\Section::make(self::u('Trang S\\u1ea3n Ph\\u1ea9m'))->schema([
                        TextInput::make('seo_products_title')->label('SEO Title')->default(self::u('T\\u1ea5t c\\u1ea3 s\\u1ea3n ph\\u1ea9m')),
                        Textarea::make('seo_products_description')->label('SEO Description')->default(self::u('Danh s\\u00e1ch m\\u00e1y may c\\u00f4ng nghi\\u1ec7p ch\\u1ea5t l\\u01b0\\u1ee3ng cao')),
                    ]),
                    \Filament\Schemas\Components\Section::make(self::u('Trang Tin T\\u1ee9c'))->schema([
                        TextInput::make('seo_news_title')->label('SEO Title')->default(self::u('Tin t\\u1ee9c & S\\u1ef1 ki\\u1ec7n')),
                        Textarea::make('seo_news_description')->label('SEO Description')->default(self::u('C\\u1eadp nh\\u1eadt tin t\\u1ee9c m\\u1edbi nh\\u1ea5t v\\u1ec1 ng\\u00e0nh may m\\u1eb7c v\\u00e0 s\\u1ef1 ki\\u1ec7n c\\u00f4ng ngh\\u1ec7.')),
                    ]),
                    \Filament\Schemas\Components\Section::make(self::u('Trang Li\\u00ean H\\u1ec7'))->schema([
                        TextInput::make('seo_contact_title')->label('SEO Title')->default(self::u('Li\\u00ean h\\u1ec7 TechSewing')),
                        Textarea::make('seo_contact_description')->label('SEO Description')->default(self::u('Nh\\u1eadn t\\u01b0 v\\u1ea5n gi\\u1ea3i ph\\u00e1p m\\u00e1y may c\\u00f4ng nghi\\u1ec7p, b\\u00e1o gi\\u00e1, demo v\\u00e0 h\\u1ed7 tr\\u1ee3 k\\u1ef9 thu\\u1eadt t\\u1eeb TechSewing.')),
                    ]),
                ]),
            ]),
        ]);
    }

    public function save(): void
    {
        $this->data = array_replace($this->data, $this->form->getState());
        $this->validate($this->settingsValidationRules(), V::messages());

        $this->persistUploadSettings();
        $this->form->fill($this->data);

        $keys = [
            'seo_default_title',
            'seo_default_description',
            'seo_default_canonical',
            'seo_default_og_image',
            'seo_organization_name',
            'seo_organization_url',
            'seo_robots_default',
            'seo_enable_schema',
            'seo_enable_og',
            'seo_products_title',
            'seo_products_description',
            'seo_news_title',
            'seo_news_description',
            'seo_contact_title',
            'seo_contact_description',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $this->data[$key] ?? null, 'group' => 'seo']);
        }

        Notification::make()->title(self::u('\\u0110\\u00e3 l\\u01b0u c\\u1ea5u h\\u00ecnh SEO.'))->success()->send();
    }

    protected function settingsValidationRules(): array
    {
        return [
            'data.seo_default_title' => V::text(70),
            'data.seo_default_description' => V::text(160),
            'data.seo_default_canonical' => ['nullable', 'url', 'max:500'],
            'data.seo_default_og_image' => ['nullable', 'string', 'max:500'],
            'data.seo_default_og_image_upload' => ['nullable'],
            'data.seo_organization_name' => V::text(),
            'data.seo_organization_url' => ['nullable', 'url', 'max:500'],
            'data.seo_robots_default' => V::text(100),
            'data.seo_enable_schema' => ['boolean'],
            'data.seo_enable_og' => ['boolean'],
            'data.seo_products_title' => V::text(70),
            'data.seo_products_description' => V::text(160),
            'data.seo_news_title' => V::text(70),
            'data.seo_news_description' => V::text(160),
            'data.seo_contact_title' => V::text(70),
            'data.seo_contact_description' => V::text(160),
        ];
    }

    protected function persistUploadSettings(): void
    {
        foreach (['seo_default_og_image_upload'] as $key) {
            $targetKey = str_replace('_upload', '', $key);
            $value = $this->normalizeUploadInput($this->data[$key] ?? null)
                ?? $this->normalizeUploadInput($this->data[$targetKey] ?? null);

            $this->data[$key] = $this->normalizeUploadFieldState($value);
            $this->data[$targetKey] = $value;

            Setting::updateOrCreate(['key' => $targetKey], ['value' => $value, 'group' => 'seo']);
        }
    }

    protected function normalizeUploadInput(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = array_values($value)[0] ?? null;
        }

        if ($value instanceof TemporaryUploadedFile) {
            return $value->store('site', 'public');
        }

        return is_string($value) && filled($value) ? $value : null;
    }

    protected function normalizeUploadFieldState(mixed $value): array
    {
        $value = $this->normalizeUploadInput($value);

        return filled($value) ? [$value] : [];
    }

    private static function u(string $value): string
    {
        $decoded = json_decode('"' . $value . '"');

        return is_string($decoded) ? $decoded : $value;
    }
}
