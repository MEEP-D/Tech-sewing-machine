<?php

namespace App\Filament\Pages;

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

class SeoSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';
    protected string $view = 'filament.pages.seo-settings';
    protected static ?string $navigationLabel = 'Cài đặt SEO';
    protected static ?string $title = 'Cấu hình SEO';

    public array $data = [];

    public function mount(): void
    {
        $ogImage = Setting::getValue('seo_default_og_image', null);
        $favicon = Setting::getValue('site_favicon', null);
        $this->data = [
            'seo_default_title' => Setting::getValue('seo_default_title', config('app.name')),
            'seo_default_description' => Setting::getValue('seo_default_description', ''),
            'seo_default_og_image_upload' => $this->normalizeUploadFieldState($ogImage),
            'seo_default_og_image' => $this->normalizeUploadInput($ogImage),
            'site_favicon_upload' => $this->normalizeUploadFieldState($favicon),
            'site_favicon' => $this->normalizeUploadInput($favicon),
            'seo_default_canonical' => Setting::getValue('seo_default_canonical', config('app.url')),
            'seo_organization_name' => Setting::getValue('seo_organization_name', config('app.name')),
            'seo_organization_url' => Setting::getValue('seo_organization_url', config('app.url')),
            'seo_robots_default' => Setting::getValue('seo_robots_default', 'index,follow'),
            'seo_enable_schema' => Setting::getValue('seo_enable_schema', true),
            'seo_enable_og' => Setting::getValue('seo_enable_og', true),
            
            // Trang tĩnh
            'seo_products_title' => Setting::getValue('seo_products_title', 'Tất cả sản phẩm'),
            'seo_products_description' => Setting::getValue('seo_products_description', 'Danh sách máy may công nghiệp chất lượng cao'),
            'seo_news_title' => Setting::getValue('seo_news_title', 'Tin tức & Sự kiện'),
            'seo_news_description' => Setting::getValue('seo_news_description', 'Cập nhật tin tức mới nhất về ngành may mặc và sự kiện công nghệ.'),
            'seo_contact_title' => Setting::getValue('seo_contact_title', 'Liên hệ TechSewing'),
            'seo_contact_description' => Setting::getValue('seo_contact_description', 'Nhận tư vấn giải pháp máy may công nghiệp, báo giá, demo và hỗ trợ kỹ thuật từ TechSewing.'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            \Filament\Schemas\Components\Tabs::make('SEO Settings')->tabs([
                \Filament\Schemas\Components\Tabs\Tab::make('Mặc định Global')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('seo_default_title')->label('SEO title mặc định'),
                        TextInput::make('seo_default_canonical')->label('Canonical mặc định'),
                        FileUpload::make('seo_default_og_image_upload')->label('OG image mặc định')->image()->disk('public')->directory('site')->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                        FileUpload::make('site_favicon_upload')->label('Logo tab trình duyệt')->image()->disk('public')->directory('site')->imageEditor()
                            ->helperText('Dùng cho favicon hiển thị trên tab trình duyệt.')
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                        TextInput::make('seo_organization_name')->label('Tên tổ chức'),
                        TextInput::make('seo_organization_url')->label('URL tổ chức'),
                        TextInput::make('seo_robots_default')->label('Robots mặc định'),
                        Checkbox::make('seo_enable_schema')->label('Bật JSON-LD schema')->default(true),
                        Checkbox::make('seo_enable_og')->label('Bật Open Graph')->default(true),
                    ]),
                    Textarea::make('seo_default_description')->label('SEO description mặc định'),
                ]),
                \Filament\Schemas\Components\Tabs\Tab::make('Trang Tĩnh (Static Pages)')->schema([
                    \Filament\Schemas\Components\Section::make('Trang Sản Phẩm')->schema([
                        TextInput::make('seo_products_title')->label('SEO Title')->default('Tất cả sản phẩm'),
                        Textarea::make('seo_products_description')->label('SEO Description')->default('Danh sách máy may công nghiệp chất lượng cao'),
                    ]),
                    \Filament\Schemas\Components\Section::make('Trang Tin Tức')->schema([
                        TextInput::make('seo_news_title')->label('SEO Title')->default('Tin tức & Sự kiện'),
                        Textarea::make('seo_news_description')->label('SEO Description')->default('Cập nhật tin tức mới nhất về ngành may mặc và sự kiện công nghệ.'),
                    ]),
                    \Filament\Schemas\Components\Section::make('Trang Liên Hệ')->schema([
                        TextInput::make('seo_contact_title')->label('SEO Title')->default('Liên hệ TechSewing'),
                        Textarea::make('seo_contact_description')->label('SEO Description')->default('Nhận tư vấn giải pháp máy may công nghiệp, báo giá, demo và hỗ trợ kỹ thuật từ TechSewing.'),
                    ]),
                ]),
            ]),
        ]);
    }


    public function save(): void
    {
        $this->data = array_replace($this->data, $this->form->getState());

        $ogImage = $this->normalizeUploadInput($this->data['seo_default_og_image_upload'] ?? null)
            ?? $this->normalizeUploadInput($this->data['seo_default_og_image'] ?? null);
        $this->data['seo_default_og_image_upload'] = $this->normalizeUploadFieldState($ogImage);
        $this->data['seo_default_og_image'] = $ogImage;

        $favicon = $this->normalizeUploadInput($this->data['site_favicon_upload'] ?? null)
            ?? $this->normalizeUploadInput($this->data['site_favicon'] ?? null);
        $this->data['site_favicon_upload'] = $this->normalizeUploadFieldState($favicon);
        $this->data['site_favicon'] = $favicon;

        $keys = [
            'seo_default_title', 'seo_default_description', 'seo_default_og_image', 'seo_default_canonical', 
            'seo_organization_name', 'seo_organization_url', 'seo_robots_default', 'seo_enable_schema', 'seo_enable_og',
            'seo_products_title', 'seo_products_description',
            'seo_news_title', 'seo_news_description',
            'seo_contact_title', 'seo_contact_description'
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $this->data[$key] ?? null, 'group' => 'seo']);
        }

        Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => $favicon, 'group' => 'branding']);

        Notification::make()->title('Đã lưu cấu hình SEO.')->success()->send();
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
}
