<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected string $view = 'filament.pages.site-settings';
    protected static ?string $navigationLabel = 'Cài đặt website';
    protected static ?string $title = 'Cấu hình website';

    public array $data = [];

    public function mount(): void
    {
        $seoOgImage = Setting::getValue('seo_default_og_image', null);

        $this->data = [
            'site_title' => Setting::getValue('site_title', config('app.name')),
            'site_description' => Setting::getValue('site_description', ''),
            'site_logo_upload' => $this->normalizeUploadFieldState(Setting::getValue('site_logo', null)),
            'site_logo_dark_upload' => $this->normalizeUploadFieldState(Setting::getValue('site_logo_dark', null)),
            'site_logo_mobile_upload' => $this->normalizeUploadFieldState(Setting::getValue('site_logo_mobile', null)),
            'site_logo_type' => Setting::getValue('site_logo_type', 'image'),
            'site_logo_height' => Setting::getValue('site_logo_height', 44),
            'site_logo_width' => Setting::getValue('site_logo_width', 180),
            'site_favicon_upload' => $this->normalizeUploadFieldState(Setting::getValue('site_favicon', null)),
            'home_hero_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('home_hero_image', null)),
            'seo_default_title' => Setting::getValue('seo_default_title', config('app.name')),
            'seo_default_description' => Setting::getValue('seo_default_description', ''),
            'seo_default_og_image_upload' => $this->normalizeUploadFieldState($seoOgImage),
            'seo_default_og_image' => $this->normalizeUploadInput($seoOgImage),
            'seo_default_canonical' => Setting::getValue('seo_default_canonical', config('app.url')),
            'seo_organization_name' => Setting::getValue('seo_organization_name', config('app.name')),
            'seo_organization_url' => Setting::getValue('seo_organization_url', config('app.url')),
            'seo_robots_default' => Setting::getValue('seo_robots_default', 'index,follow'),
            'seo_description' => Setting::getValue('seo_description', ''),
            
            // Trang nội dung
            'page_contact_kicker' => Setting::getValue('page_contact_kicker', 'Liên hệ & thu thập khách hàng tiềm năng'),
            'page_contact_heading' => Setting::getValue('page_contact_heading', 'Đặt lịch tư vấn, demo giải pháp và nhận báo giá nhanh'),
            'page_contact_desc' => Setting::getValue('page_contact_desc', 'Hãy để lại thông tin để đội ngũ chuyên gia của chúng tôi hỗ trợ bạn tốt nhất.'),
            'page_products_kicker' => Setting::getValue('page_products_kicker', 'Product experience'),
            'page_products_heading' => Setting::getValue('page_products_heading', 'Khám phá lineup máy may công nghiệp'),
            'page_products_desc' => Setting::getValue('page_products_desc', 'Cung cấp các dòng máy may chính hãng, chất lượng cao đáp ứng mọi nhu cầu sản xuất.'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Nhận diện thương hiệu')->schema([
                Grid::make(2)->schema([
                    TextInput::make('site_title')->label('Tiêu đề website'),
                    TextInput::make('site_description')->label('Mô tả website'),
                    TextInput::make('site_logo_type')->label('Loại logo')->placeholder('image | text'),
                    TextInput::make('site_logo_height')->label('Chiều cao logo')->numeric(),
                    TextInput::make('site_logo_width')->label('Chiều rộng logo')->numeric(),
                    FileUpload::make('site_logo_upload')->label('Logo sáng')->image()->disk('public')->directory('site')->imageEditor()
                        ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('site_logo_dark_upload')->label('Logo tối')->image()->disk('public')->directory('site')->imageEditor()
                        ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('site_logo_mobile_upload')->label('Logo mobile')->image()->disk('public')->directory('site')->imageEditor()
                        ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('site_favicon_upload')->label('Favicon')->image()->disk('public')->directory('site')->imageEditor()
                        ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('home_hero_image_upload')->label('Ảnh hero')->image()->disk('public')->directory('site')->imageEditor()
                        ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                ]),
            ]),
            Section::make('SEO mặc định')->schema([
                Grid::make(2)->schema([
                    TextInput::make('seo_default_title')->label('SEO title mặc định'),
                    TextInput::make('seo_default_canonical')->label('Canonical mặc định'),
                    FileUpload::make('seo_default_og_image_upload')->label('OG image mặc định')->image()->disk('public')->directory('site')->imageEditor()
                        ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    TextInput::make('seo_organization_name')->label('Tên tổ chức'),
                    TextInput::make('seo_organization_url')->label('URL tổ chức'),
                    TextInput::make('seo_robots_default')->label('Robots mặc định'),
                ]),
                Textarea::make('seo_default_description')->label('SEO description mặc định'),
                Textarea::make('seo_description')->label('SEO description bổ sung'),
            ]),
            Section::make('Nội dung trang Liên hệ')->schema([
                TextInput::make('page_contact_kicker')->label('Dòng phụ (Kicker)')->default('Liên hệ & thu thập khách hàng tiềm năng'),
                TextInput::make('page_contact_heading')->label('Tiêu đề chính')->default('Đặt lịch tư vấn, demo giải pháp và nhận báo giá nhanh'),
                Textarea::make('page_contact_desc')->label('Mô tả ngắn')->default('Hãy để lại thông tin để đội ngũ chuyên gia của chúng tôi hỗ trợ bạn tốt nhất.'),
            ]),
            Section::make('Nội dung trang Sản phẩm')->schema([
                TextInput::make('page_products_kicker')->label('Dòng phụ (Kicker)')->default('Product experience'),
                TextInput::make('page_products_heading')->label('Tiêu đề chính')->default('Khám phá lineup máy may công nghiệp'),
                Textarea::make('page_products_desc')->label('Mô tả ngắn')->default('Cung cấp các dòng máy may chính hãng, chất lượng cao đáp ứng mọi nhu cầu sản xuất.'),
            ]),
        ]);
    }

    public function save(): void
    {
        $this->persistUploadSettings();
        $this->persistTextSettings();
        Cache::forget('site_settings_array');

        Notification::make()->title('Đã lưu cấu hình website.')->success()->send();
    }

    protected function persistUploadSettings(): void
    {
        foreach (['site_logo_upload', 'site_logo_dark_upload', 'site_logo_mobile_upload', 'site_favicon_upload', 'home_hero_image_upload', 'seo_default_og_image_upload'] as $key) {
            $targetKey = str_replace('_upload', '', $key);
            $value = $this->normalizeUploadInput($this->data[$key] ?? null)
                ?? $this->normalizeUploadInput($this->data[$targetKey] ?? null);
            $this->data[$key] = $this->normalizeUploadFieldState($value);
            $this->data[$targetKey] = $value;
            $group = str_starts_with($targetKey, 'seo_') ? 'seo' : (in_array($targetKey, ['site_logo', 'site_logo_dark', 'site_logo_mobile', 'site_favicon'], true) ? 'branding' : 'homepage');

            Setting::updateOrCreate(['key' => $targetKey], ['value' => $value, 'group' => $group]);
        }
    }

    protected function persistTextSettings(): void
    {
        $keys = [
            'site_title', 'site_description', 'site_logo_type', 'site_logo_height', 'site_logo_width', 
            'seo_default_title', 'seo_default_description', 'seo_default_canonical', 'seo_organization_name', 'seo_organization_url', 'seo_robots_default', 'seo_description',
            'page_contact_kicker', 'page_contact_heading', 'page_contact_desc',
            'page_products_kicker', 'page_products_heading', 'page_products_desc'
        ];
        foreach ($keys as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $this->data[$key] ?? null, 'group' => str_starts_with($key, 'seo_') ? 'seo' : 'branding']);
        }
    }

    protected function normalizeUploadState(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_values($value)[0] ?? null;
        }

        return filled($value) ? $value : null;
    }

    protected function normalizeUploadInput(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = array_values($value)[0] ?? null;
        }

        return is_string($value) && filled($value) ? $value : null;
    }

    protected function normalizeUploadFieldState(mixed $value): array
    {
        $value = $this->normalizeUploadInput($value);

        return filled($value) ? [$value] : [];
    }

    public function previewAsset(mixed $value): ?string
    {
        $path = $this->normalizeUploadState($value);
        if (is_array($path)) {
            $path = array_values($path)[0] ?? null;
        }
        if (! is_string($path) || ! filled($path)) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}
