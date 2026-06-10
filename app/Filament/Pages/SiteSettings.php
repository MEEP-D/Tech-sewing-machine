<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\Page as SitePage;
use App\Models\Post;
use App\Models\Product;
use App\Services\DynamicMailConfigService;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\View;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected string $view = 'filament.pages.site-settings';

    public static function getNavigationLabel(): string
    {
        return self::u('C\\u00e0i \\u0111\\u1eb7t website');
    }

    public function getTitle(): string
    {
        return self::u('C\\u1ea5u h\\u00ecnh website');
    }

    public array $data = [];
    public string $testEmail = '';

    public function mount(): void
    {
        $siteLogo = Setting::getValue('site_logo', null);
        $siteLogoDark = Setting::getValue('site_logo_dark', null);
        $siteLogoMobile = Setting::getValue('site_logo_mobile', null);
        $siteFavicon = Setting::getValue('site_favicon', null);
        $homeHeroImage = Setting::getValue('home_hero_image', null);

        $this->data = [
            'site_title' => Setting::getValue('site_title', config('app.name')),
            'site_description' => Setting::getValue('site_description', ''),
            'site_logo_height' => Setting::getValue('site_logo_height', 48),
            'site_logo_width' => Setting::getValue('site_logo_width', 180),
            'site_logo_upload' => $this->normalizeUploadFieldState($siteLogo),
            'site_logo' => $this->normalizeUploadInput($siteLogo),
            'site_logo_dark_upload' => $this->normalizeUploadFieldState($siteLogoDark),
            'site_logo_dark' => $this->normalizeUploadInput($siteLogoDark),
            'site_logo_mobile_upload' => $this->normalizeUploadFieldState($siteLogoMobile),
            'site_logo_mobile' => $this->normalizeUploadInput($siteLogoMobile),
            'site_logo_type' => Setting::getValue('site_logo_type', 'image'),
            'site_favicon_upload' => $this->normalizeUploadFieldState($siteFavicon),
            'site_favicon' => $this->normalizeUploadInput($siteFavicon),
            'seo_default_title' => Setting::getValue('seo_default_title', config('app.name')),
            'seo_default_description' => Setting::getValue('seo_default_description', ''),
            'seo_default_canonical' => Setting::getValue('seo_default_canonical', config('app.url')),
            'seo_default_og_image' => Setting::getValue('seo_default_og_image', ''),
            'seo_organization_name' => Setting::getValue('seo_organization_name', config('app.name')),
            'seo_organization_url' => Setting::getValue('seo_organization_url', config('app.url')),
            'seo_robots_default' => Setting::getValue('seo_robots_default', 'index,follow'),
            'seo_description' => Setting::getValue('seo_description', ''),
            'home_hero_image_upload' => $this->normalizeUploadFieldState($homeHeroImage),
            'home_hero_image' => $this->normalizeUploadInput($homeHeroImage),
            'home_service_title' => Setting::getValue('home_service_title', ''),
            'home_service_description' => Setting::getValue('home_service_description', ''),
            'home_service_primary_cta' => Setting::getValue('home_service_primary_cta', ''),
            'home_service_secondary_cta' => Setting::getValue('home_service_secondary_cta', ''),
            'home_highlight_contact_primary_name' => Setting::getValue('home_highlight_contact_primary_name', self::u('Mr. S\\u00e1ng')),
            'home_highlight_contact_primary_phone' => Setting::getValue('home_highlight_contact_primary_phone', '0902 806 599'),
            'home_highlight_contact_secondary_name' => Setting::getValue('home_highlight_contact_secondary_name', self::u('Mr. B\\u1ea3o')),
            'home_highlight_contact_secondary_phone' => Setting::getValue('home_highlight_contact_secondary_phone', '0898 303 287'),
            'contact_hotline' => Setting::getValue('contact_hotline', ''),
            'contact_email' => Setting::getValue('contact_email', ''),
            'contact_address' => Setting::getValue('contact_address', ''),
            'mail_mailer' => Setting::getValue('mail_mailer', 'smtp'),
            'mail_host' => Setting::getValue('mail_host', ''),
            'mail_port' => Setting::getValue('mail_port', '587'),
            'mail_encryption' => Setting::getValue('mail_encryption', 'tls'),
            'mail_username' => Setting::getValue('mail_username', ''),
            'mail_password' => '',
            'mail_from_address' => Setting::getValue('mail_from_address', ''),
            'mail_from_name' => Setting::getValue('mail_from_name', config('app.name')),
            'mail_template_source_type' => Setting::getValue('mail_template_source_type', 'post'),
            'mail_template_source_id' => Setting::getValue('mail_template_source_id', ''),
            'mail_template_source_url' => Setting::getValue('mail_template_source_url', ''),
            'mail_template_subject' => Setting::getValue('mail_template_subject', 'Nội dung mới từ TechSewing'),
            'mail_template_html' => Setting::getValue('mail_template_html', '<h2>{{title}}</h2><p>{{excerpt}}</p><p><a href="{{url}}">Xem chi tiết</a></p>'),
            'header_facebook_url' => Setting::getValue('header_facebook_url', ''),
            'header_zalo_url' => Setting::getValue('header_zalo_url', ''),
            'header_youtube_url' => Setting::getValue('header_youtube_url', ''),
            'page_contact_kicker' => Setting::getValue('page_contact_kicker', self::u('Li\\u00ean h\\u1ec7 & thu th\\u1eadp kh\\u00e1ch h\\u00e0ng ti\\u1ec1m n\\u0103ng')),
            'page_contact_heading' => Setting::getValue('page_contact_heading', self::u('\\u0110\\u1eb7t l\\u1ecbch t\\u01b0 v\\u1ea5n, demo gi\\u1ea3i ph\\u00e1p v\\u00e0 nh\\u1eadn b\\u00e1o gi\\u00e1 nhanh')),
            'page_contact_desc' => Setting::getValue('page_contact_desc', self::u('H\\u00e3y \\u0111\\u1ec3 l\\u1ea1i th\\u00f4ng tin \\u0111\\u1ec3 \\u0111\\u1ed9i ng\\u0169 chuy\\u00ean gia c\\u1ee7a ch\\u00fang t\\u00f4i h\\u1ed7 tr\\u1ee3 b\\u1ea1n t\\u1ed1t nh\\u1ea5t.')),
            'page_products_kicker' => Setting::getValue('page_products_kicker', 'Trải nghiệm sản phẩm'),
            'page_products_heading' => Setting::getValue('page_products_heading', self::u('Kh\\u00e1m ph\\u00e1 lineup m\\u00e1y may c\\u00f4ng nghi\\u1ec7p')),
            'page_products_desc' => Setting::getValue('page_products_desc', self::u('Cung c\\u1ea5p c\\u00e1c d\\u00f2ng m\\u00e1y may ch\\u00ednh h\\u00e3ng, ch\\u1ea5t l\\u01b0\\u1ee3ng cao \\u0111\\u00e1p \\u1ee9ng m\\u1ecdi nhu c\\u1ea7u s\\u1ea3n xu\\u1ea5t.')),
            'page_products_hero_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('page_products_hero_image', null)),
            'page_news_heading' => Setting::getValue('page_news_heading', 'Tin tức'),
            'page_news_desc' => Setting::getValue('page_news_desc', 'Cập nhật nhanh thị trường, sản phẩm và hướng dẫn vận hành thực tế cho xưởng may.'),
            'page_news_hero_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('page_news_hero_image', null)),
            'page_about_heading' => Setting::getValue('page_about_heading', self::u('Gi\\u1edbi thi\\u1ec7u')),
            'page_about_desc' => Setting::getValue('page_about_desc', ''),
            'page_about_hero_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('page_about_hero_image', null)),
            'page_contact_hero_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('page_contact_hero_image', null)),
            'newsletter_signup_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('newsletter_signup_image', null)),
            'promo_popup_enabled' => (string) Setting::getValue('promo_popup_enabled', '0'),
            'promo_popup_title' => Setting::getValue('promo_popup_title', 'Ưu đãi dành cho khách hàng mới'),
            'promo_popup_description' => Setting::getValue('promo_popup_description', 'Nhận tư vấn nhanh và báo giá theo nhu cầu xưởng may của bạn.'),
            'promo_popup_button_text' => Setting::getValue('promo_popup_button_text', 'Nhận ưu đãi ngay'),
            'promo_popup_button_url' => Setting::getValue('promo_popup_button_url', ''),
            'promo_popup_contact_text' => Setting::getValue('promo_popup_contact_text', 'Liên hệ ngay'),
            'promo_popup_contact_url' => Setting::getValue('promo_popup_contact_url', ''),
            'promo_popup_countdown_end_at' => Setting::getValue('promo_popup_countdown_end_at', ''),
            'promo_popup_countdown_note' => Setting::getValue('promo_popup_countdown_note', '(*) Chương trình áp dụng có điều kiện đến hết ngày.'),
            'promo_popup_image_upload' => $this->normalizeUploadFieldState(Setting::getValue('promo_popup_image', null)),
            'promo_popup_images_upload' => $this->normalizeUploadListFieldState(Setting::getValue('promo_popup_images', [])),
            'promo_popup_delay_seconds' => (string) Setting::getValue('promo_popup_delay_seconds', '2'),
            'promo_popup_frequency_hours' => (string) Setting::getValue('promo_popup_frequency_hours', '24'),
        ];

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Tabs::make('Site Settings')->tabs([
                Tabs\Tab::make(self::u('T\\u1ed5ng quan'))->schema([
                    Section::make(self::u('Nh\\u1eadn di\\u1ec7n th\\u01b0\\u01a1ng hi\\u1ec7u'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('site_title')->label(self::u('Ti\\u00eau \\u0111\\u1ec1 website')),
                            TextInput::make('site_description')->label(self::u('M\\u00f4 t\\u1ea3 website')),
                            Select::make('site_logo_type')
                                ->label(self::u('Lo\\u1ea1i logo'))
                                ->options([
                                    'image' => 'Image',
                                    'text' => 'Text',
                                ])
                                ->required()
                                ->default('image')
                                ->native(false),
                            FileUpload::make('site_logo_upload')->label(self::u('Logo s\\u00e1ng'))->image()->disk('public')->directory('site')->imageEditor()
                                ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                            FileUpload::make('site_logo_dark_upload')->label(self::u('Logo t\\u1ed1i'))->image()->disk('public')->directory('site')->imageEditor()
                                ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                            FileUpload::make('site_logo_mobile_upload')->label(self::u('Logo mobile'))->image()->disk('public')->directory('site')->imageEditor()
                                ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                            FileUpload::make('site_favicon_upload')->label('Favicon')->image()->disk('public')->directory('site')->imageEditor()
                                ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                        ]),
                    ]),
                    Section::make(self::u('SEO m\\u1eb7c \\u0111\\u1ecbnh'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('seo_default_title')->label(self::u('SEO title m\\u1eb7c \\u0111\\u1ecbnh')),
                            TextInput::make('seo_default_canonical')->label(self::u('Canonical m\\u1eb7c \\u0111\\u1ecbnh')),
                            TextInput::make('seo_default_og_image')->label(self::u('\\u1ea2nh OG m\\u1eb7c \\u0111\\u1ecbnh'))->maxLength(500),
                            TextInput::make('seo_organization_name')->label(self::u('T\\u00ean t\\u1ed5 ch\\u1ee9c')),
                            TextInput::make('seo_organization_url')->label(self::u('URL t\\u1ed5 ch\\u1ee9c')),
                            TextInput::make('seo_robots_default')->label(self::u('Robots m\\u1eb7c \\u0111\\u1ecbnh')),
                        ]),
                        Textarea::make('seo_default_description')->label(self::u('SEO description m\\u1eb7c \\u0111\\u1ecbnh')),
                        Textarea::make('seo_description')->label(self::u('SEO description b\\u1ed5 sung')),
                    ]),
                    Section::make(self::u('Li\\u00ean h\\u1ec7 & M\\u1ea1ng x\\u00e3 h\\u1ed9i header'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('contact_hotline')->label('Hotline header'),
                            TextInput::make('contact_email')->label(self::u('Email li\\u00ean h\\u1ec7')),
                            Textarea::make('contact_address')->label(self::u('\\u0110\\u1ecba ch\\u1ec9')),
                            TextInput::make('header_facebook_url')->label('Link Facebook')->url(),
                            TextInput::make('header_zalo_url')->label('Link Zalo')->url(),
                            TextInput::make('header_youtube_url')->label('Link YouTube')->url(),
                        ]),
                    ]),
                ]),
                Tabs\Tab::make(self::u('Trang ch\\u1ee7'))->schema([
                    Section::make(self::u('\\u1ea2nh hero trang ch\\u1ee7'))->schema([
                        FileUpload::make('home_hero_image_upload')
                            ->label(self::u('\\u1ea2nh n\\u1ec1n hero'))
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    ]),
                    Section::make(self::u('Li\\u00ean h\\u1ec7 s\\u1ea3n ph\\u1ea9m \\u0111\\u1ed9t ph\\u00e1'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('home_highlight_contact_primary_name')->label(self::u('T\\u00ean li\\u00ean h\\u1ec7 1')),
                            TextInput::make('home_highlight_contact_primary_phone')->label(self::u('S\\u1ed1 \\u0111i\\u1ec7n tho\\u1ea1i 1')),
                            TextInput::make('home_highlight_contact_secondary_name')->label(self::u('T\\u00ean li\\u00ean h\\u1ec7 2')),
                            TextInput::make('home_highlight_contact_secondary_phone')->label(self::u('S\\u1ed1 \\u0111i\\u1ec7n tho\\u1ea1i 2')),
                        ]),
                    ]),
                    Section::make(self::u('Service banner trang ch\\u1ee7'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('home_service_title')->label(self::u('Ti\\u00eau \\u0111\\u1ec1 section')),
                            TextInput::make('home_service_primary_cta')->label(self::u('Text n\\u00fat CTA ch\\u00ednh')),
                            Textarea::make('home_service_description')
                                ->label(self::u('M\\u00f4 t\\u1ea3 section'))
                                ->rows(4)
                                ->columnSpanFull(),
                            TextInput::make('home_service_secondary_cta')->label(self::u('Text n\\u00fat CTA ph\\u1ee5')),
                        ]),
                    ]),
                    Section::make(self::u('Newsletter Signup'))->schema([
                        FileUpload::make('newsletter_signup_image_upload')
                            ->label(self::u('\\u1ea2nh n\\u1ec1n section newsletter'))
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    ]),
                ]),
                Tabs\Tab::make(self::u('Trang t\\u0129nh'))->schema([
                    Section::make(self::u('N\\u1ed9i dung trang Li\\u00ean h\\u1ec7'))->schema([
                        TextInput::make('page_contact_kicker')->label(self::u('D\\u00f2ng ph\\u1ee5 (Kicker)'))->default(self::u('Li\\u00ean h\\u1ec7 & thu th\\u1eadp kh\\u00e1ch h\\u00e0ng ti\\u1ec1m n\\u0103ng')),
                        TextInput::make('page_contact_heading')->label(self::u('Ti\\u00eau \\u0111\\u1ec1 ch\\u00ednh'))->default(self::u('\\u0110\\u1eb7t l\\u1ecbch t\\u01b0 v\\u1ea5n, demo gi\\u1ea3i ph\\u00e1p v\\u00e0 nh\\u1eadn b\\u00e1o gi\\u00e1 nhanh')),
                        Textarea::make('page_contact_desc')->label(self::u('M\\u00f4 t\\u1ea3 ng\\u1eafn'))->default(self::u('H\\u00e3y \\u0111\\u1ec3 l\\u1ea1i th\\u00f4ng tin \\u0111\\u1ec3 \\u0111\\u1ed9i ng\\u0169 chuy\\u00ean gia c\\u1ee7a ch\\u00fang t\\u00f4i h\\u1ed7 tr\\u1ee3 b\\u1ea1n t\\u1ed1t nh\\u1ea5t.')),
                        FileUpload::make('page_contact_hero_image_upload')
                            ->label(self::u('\\u1ea2nh n\\u1ec1n hero'))
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    ]),
                    Section::make(self::u('N\\u1ed9i dung trang S\\u1ea3n ph\\u1ea9m'))->schema([
                        TextInput::make('page_products_kicker')->label(self::u('D\\u00f2ng ph\\u1ee5 (Kicker)'))->default('Trải nghiệm sản phẩm'),
                        TextInput::make('page_products_heading')->label(self::u('Ti\\u00eau \\u0111\\u1ec1 ch\\u00ednh'))->default(self::u('Kh\\u00e1m ph\\u00e1 lineup m\\u00e1y may c\\u00f4ng nghi\\u1ec7p')),
                        Textarea::make('page_products_desc')->label(self::u('M\\u00f4 t\\u1ea3 ng\\u1eafn'))->default(self::u('Cung c\\u1ea5p c\\u00e1c d\\u00f2ng m\\u00e1y may ch\\u00ednh h\\u00e3ng, ch\\u1ea5t l\\u01b0\\u1ee3ng cao \\u0111\\u00e1p \\u1ee9ng m\\u1ecdi nhu c\\u1ea7u s\\u1ea3n xu\\u1ea5t.')),
                        FileUpload::make('page_products_hero_image_upload')
                            ->label(self::u('\\u1ea2nh n\\u1ec1n hero'))
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    ]),
                    Section::make(self::u('N\\u1ed9i dung trang Tin t\\u1ee9c'))->schema([
                        TextInput::make('page_news_heading')->label(self::u('Ti\\u00eau \\u0111\\u1ec1 hero'))->default('Tin tức'),
                        Textarea::make('page_news_desc')->label(self::u('M\\u00f4 t\\u1ea3 hero'))->default('Cập nhật nhanh thị trường, sản phẩm và hướng dẫn vận hành thực tế cho xưởng may.'),
                        FileUpload::make('page_news_hero_image_upload')
                            ->label(self::u('\\u1ea2nh n\\u1ec1n hero'))
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    ]),
                    Section::make(self::u('N\\u1ed9i dung trang Gi\\u1edbi thi\\u1ec7u'))->schema([
                        TextInput::make('page_about_heading')->label(self::u('Ti\\u00eau \\u0111\\u1ec1 hero'))->default(self::u('Gi\\u1edbi thi\\u1ec7u')),
                        Textarea::make('page_about_desc')->label(self::u('M\\u00f4 t\\u1ea3 hero')),
                        FileUpload::make('page_about_hero_image_upload')
                            ->label(self::u('\\u1ea2nh n\\u1ec1n hero'))
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    ]),
                ]),
                Tabs\Tab::make('Popup khuyến mãi')->schema([
                    Section::make('Cấu hình popup khuyến mãi')->schema([
                        Grid::make(2)->schema([
                            Select::make('promo_popup_enabled')
                                ->label('Bật popup')
                                ->options([
                                    '1' => 'Bật',
                                    '0' => 'Tắt',
                                ])
                                ->default('0')
                                ->native(false),
                            TextInput::make('promo_popup_delay_seconds')
                                ->label('Độ trễ hiển thị (giây)')
                                ->numeric()
                                ->minValue(0)
                                ->default('2'),
                            TextInput::make('promo_popup_frequency_hours')
                                ->label('Tần suất hiện lại (giờ)')
                                ->numeric()
                                ->minValue(1)
                                ->default('24'),
                            TextInput::make('promo_popup_button_url')->label('Link nút CTA')->url(),

                            TextInput::make('promo_popup_contact_url')->label('Link nút liên hệ')->url(),
                            TextInput::make('promo_popup_countdown_end_at')
                                ->label('Thời điểm kết thúc đếm ngược')
                                ->type('datetime-local'),
                        ]),
                        TextInput::make('promo_popup_title')->label('Tiêu đề popup'),
                        Textarea::make('promo_popup_description')->label('Mô tả popup'),
                        TextInput::make('promo_popup_button_text')->label('Text nút CTA'),

                        TextInput::make('promo_popup_contact_text')->label('Text nút liên hệ'),
                        TextInput::make('promo_popup_countdown_note')->label('Ghi chú đếm ngược'),
                        FileUpload::make('promo_popup_image_upload')
                            ->label('Ảnh popup')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->imageEditor()
                            ->afterStateHydrated(fn ($component, $state) => $component->state(filled($state) ? [$state] : []))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                        FileUpload::make('promo_popup_images_upload')
                            ->label('Danh sách ảnh popup (tự động chạy 3 giây/ảnh)')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->disk('public')
                            ->directory('site')
                            ->helperText('Nếu có nhiều ảnh, popup sẽ tự chạy slideshow 3 giây mỗi ảnh.')
                            ->afterStateHydrated(function ($component, $state): void {
                                $component->state($this->normalizeUploadListFieldState($state));
                            }),
                    ]),
                ]),
                Tabs\Tab::make('SMTP / Mail')->schema([
                    Section::make('SMTP / Mail')->schema([
                        Grid::make(2)->schema([
                            Select::make('mail_mailer')
                                ->label('Mailer')
                                ->options([
                                    'smtp' => 'SMTP',
                                ])
                                ->default('smtp')
                                ->required()
                                ->native(false),
                            TextInput::make('mail_host')->label('SMTP Host'),
                            TextInput::make('mail_port')->label('SMTP Port')->numeric(),
                            Select::make('mail_encryption')
                                ->label('Encryption')
                                ->options([
                                    'tls' => 'TLS',
                                    'ssl' => 'SSL',
                                    '' => 'None',
                                ])
                                ->default('tls')
                                ->native(false),
                            TextInput::make('mail_username')->label('SMTP Username'),
                            TextInput::make('mail_password')
                                ->label('SMTP Password')
                                ->password()
                                ->revealable()
                                ->placeholder('Để trống để giữ mật khẩu hiện tại'),
                            TextInput::make('mail_from_address')->label('From Email')->email(),
                            TextInput::make('mail_from_name')->label('From Name'),
                        ]),
                        Section::make('HTML Mail Template')->schema([
                            Grid::make(2)->schema([
                                Select::make('mail_template_source_type')
                                    ->label('Nguồn dữ liệu')
                                    ->options([
                                        'post' => 'Bài viết',
                                        'product' => 'Sản phẩm',
                                        'page' => 'Trang tĩnh',
                                        'url' => 'URL động khác',
                                    ])
                                    ->default('post')
                                    ->native(false)
                                    ->live(),
                                TextInput::make('mail_template_source_url')
                                    ->label('URL động')
                                    ->url()
                                    ->visible(fn (callable $get): bool => $get('mail_template_source_type') === 'url')
                                    ->helperText('Chỉ dùng khi chọn nguồn URL động.'),
                                Select::make('mail_template_source_id')
                                    ->label('Nội dung nguồn')
                                    ->options(fn (callable $get): array => $this->sourceOptions((string) $get('mail_template_source_type')))
                                    ->searchable()
                                    ->native(false)
                                    ->visible(fn (callable $get): bool => in_array($get('mail_template_source_type'), ['post', 'product', 'page'], true)),
                                TextInput::make('mail_template_subject')
                                    ->label('Tiêu đề mail'),
                            ]),
                            Textarea::make('mail_template_html')
                                ->label('HTML template')
                                ->rows(12)
                                ->helperText('Biến hỗ trợ: {{title}}, {{excerpt}}, {{content}}, {{url}}, {{image_url}}, {{site_name}}'),
                        ]),
                        View::make('filament.pages.partials.smtp-test-email'),
                    ]),
                ]),
            ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        if (is_array($state) && $state !== []) {
            $this->data = array_replace($this->data, $state);
        }

        $this->persistUploadSettings();
        $this->persistMailSettings();
        $this->persistTextSettings();
        $this->form->fill($this->data);
        Cache::forget('site_settings_array');

        Notification::make()->title(self::u('\\u0110\\u00e3 l\\u01b0u c\\u1ea5u h\\u00ecnh website.'))->success()->send();
    }

    public function sendTestEmail(): void
    {
        $email = trim($this->testEmail);
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Notification::make()->title('Email nhận thử không hợp lệ.')->danger()->send();
            return;
        }

        $this->persistMailSettings();
        $this->persistTextSettings();
        Cache::forget('site_settings_array');

        try {
            app(DynamicMailConfigService::class)->apply();

            $payload = $this->resolveTemplatePayload();
            $subject = (string) ($this->data['mail_template_subject'] ?? 'SMTP test - TechSewing');
            $html = $this->renderMailTemplate($payload);

            Mail::html($html, function ($message) use ($email, $subject): void {
                $message->to($email)->subject($subject);
            });

            Notification::make()->title('Đã gửi email test thành công.')->success()->send();
        } catch (\Throwable $exception) {
            Notification::make()->title('Gửi email test thất bại: ' . $exception->getMessage())->danger()->send();
        }
    }

    protected function persistUploadSettings(): void
    {
        foreach ([
            'site_logo_upload',
            'site_logo_dark_upload',
            'site_logo_mobile_upload',
            'site_favicon_upload',
            'home_hero_image_upload',
            'page_news_hero_image_upload',
            'page_products_hero_image_upload',
            'page_about_hero_image_upload',
            'page_contact_hero_image_upload',
            'newsletter_signup_image_upload',
            'promo_popup_image_upload',
        ] as $key) {
            $targetKey = str_replace('_upload', '', $key);
            $value = $this->normalizeUploadInput($this->data[$key] ?? null)
                ?? $this->normalizeUploadInput($this->data[$targetKey] ?? null);
            $this->data[$key] = $this->normalizeUploadFieldState($value);
            $this->data[$targetKey] = $value;
            $group = in_array($targetKey, ['site_logo', 'site_logo_dark', 'site_logo_mobile', 'site_favicon'], true) ? 'branding' : 'homepage';

            Setting::updateOrCreate(['key' => $targetKey], ['value' => $value, 'group' => $group]);
        }

        $promoImages = $this->normalizeUploadListInput($this->data['promo_popup_images_upload'] ?? []);
        $this->data['promo_popup_images_upload'] = $promoImages;
        Setting::updateOrCreate(['key' => 'promo_popup_images'], ['value' => json_encode($promoImages, JSON_UNESCAPED_SLASHES), 'group' => 'homepage']);
    }

    protected function persistTextSettings(): void
    {
        $keys = [
            'site_title', 'site_description', 'site_logo_type', 'site_logo_height', 'site_logo_width',
            'seo_default_title', 'seo_default_description', 'seo_default_canonical', 'seo_default_og_image', 'seo_organization_name', 'seo_organization_url', 'seo_robots_default', 'seo_description',
            'home_service_title', 'home_service_description', 'home_service_primary_cta', 'home_service_secondary_cta',
            'home_highlight_contact_primary_name', 'home_highlight_contact_primary_phone',
            'home_highlight_contact_secondary_name', 'home_highlight_contact_secondary_phone',
            'contact_hotline', 'contact_email', 'contact_address',
            'mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_from_address', 'mail_from_name',
            'mail_template_source_type', 'mail_template_source_id', 'mail_template_source_url', 'mail_template_subject', 'mail_template_html',
            'header_facebook_url', 'header_zalo_url', 'header_youtube_url',
            'page_contact_kicker', 'page_contact_heading', 'page_contact_desc',
            'page_products_kicker', 'page_products_heading', 'page_products_desc',
            'page_news_heading', 'page_news_desc',
            'page_about_heading', 'page_about_desc',
            'promo_popup_enabled', 'promo_popup_title', 'promo_popup_description',
            'promo_popup_button_text', 'promo_popup_button_url', 'promo_popup_contact_text', 'promo_popup_contact_url', 'promo_popup_countdown_end_at', 'promo_popup_countdown_note', 'promo_popup_delay_seconds', 'promo_popup_frequency_hours',
        ];

        foreach ($keys as $key) {
            $group = str_starts_with($key, 'seo_') ? 'seo' : (str_starts_with($key, 'mail_') ? 'mail' : 'branding');
            Setting::updateOrCreate(['key' => $key], ['value' => $this->data[$key] ?? null, 'group' => $group]);
        }
    }

    protected function persistMailSettings(): void
    {
        $newPassword = trim((string) ($this->data['mail_password'] ?? ''));
        if ($newPassword === '') {
            return;
        }

        Setting::updateOrCreate(
            ['key' => 'mail_password'],
            ['value' => Crypt::encryptString($newPassword), 'group' => 'mail']
        );

        $this->data['mail_password'] = '';
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
        if ($value instanceof TemporaryUploadedFile) {
            return $value->store('site', 'public');
        }

        if ($value instanceof UploadedFile) {
            return $value->store('site', 'public');
        }

        if (is_array($value)) {
            if (array_key_exists('path', $value) && is_string($value['path']) && filled($value['path'])) {
                return $value['path'];
            }

            foreach ($value as $item) {
                $normalized = $this->normalizeUploadInput($item);
                if (filled($normalized)) {
                    return $normalized;
                }
            }

            return null;
        }

        return is_string($value) && filled($value) ? $value : null;
    }

    protected function normalizeUploadFieldState(mixed $value): array
    {
        $value = $this->normalizeUploadInput($value);

        return filled($value) ? [$value] : [];
    }

    protected function normalizeUploadListInput(mixed $value): array
    {
        if (is_string($value) && filled($value)) {
            return [$value];
        }

        if (! is_array($value)) {
            return [];
        }

        $paths = [];
        foreach ($value as $item) {
            $normalized = $this->normalizeUploadInput($item);
            if (is_string($normalized) && filled($normalized)) {
                $paths[] = $normalized;
            }
        }

        return array_values(array_unique($paths));
    }

    protected function normalizeUploadListFieldState(mixed $value): array
    {
        return $this->normalizeUploadListInput($value);
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

    private static function u(string $value): string
    {
        $decoded = json_decode('"' . $value . '"');

        return is_string($decoded) ? $decoded : $value;
    }

    protected function sourceOptions(string $type): array
    {
        return match ($type) {
            'product' => Product::query()
                ->where('status', 'published')
                ->latest('id')
                ->limit(100)
                ->pluck('name', 'id')
                ->toArray(),
            'page' => SitePage::query()
                ->where('is_active', true)
                ->latest('id')
                ->limit(100)
                ->pluck('title', 'id')
                ->toArray(),
            default => Post::query()
                ->where('status', 'published')
                ->latest('id')
                ->limit(100)
                ->pluck('title', 'id')
                ->toArray(),
        };
    }

    protected function resolveTemplatePayload(): array
    {
        $type = (string) ($this->data['mail_template_source_type'] ?? 'post');
        $sourceId = (int) ($this->data['mail_template_source_id'] ?? 0);
        $fallback = [
            'title' => 'TechSewing',
            'excerpt' => 'Nội dung được gửi từ hệ thống.',
            'content' => '',
            'url' => config('app.url'),
            'image_url' => '',
            'site_name' => (string) Setting::getValue('site_title', config('app.name')),
        ];

        if ($type === 'post' && $sourceId > 0) {
            $post = Post::query()->find($sourceId);
            if ($post) {
                return [
                    'title' => (string) $post->title,
                    'excerpt' => (string) ($post->excerpt ?: Str::limit(strip_tags((string) $post->content), 200)),
                    'content' => (string) ($post->rendered_content ?? $post->content ?? ''),
                    'url' => (string) $post->url,
                    'image_url' => (string) ($post->thumbnail_url ?? ''),
                    'site_name' => $fallback['site_name'],
                ];
            }
        }

        if ($type === 'product' && $sourceId > 0) {
            $product = Product::query()->find($sourceId);
            if ($product) {
                $content = (string) ($product->long_description ?: $product->description ?: '');
                return [
                    'title' => (string) $product->name,
                    'excerpt' => Str::limit(strip_tags((string) ($product->short_description ?: $content)), 200),
                    'content' => $content,
                    'url' => (string) $product->url,
                    'image_url' => (string) ($product->display_image_url ?? ''),
                    'site_name' => $fallback['site_name'],
                ];
            }
        }

        if ($type === 'page' && $sourceId > 0) {
            $page = SitePage::query()->find($sourceId);
            if ($page) {
                return [
                    'title' => (string) $page->title,
                    'excerpt' => Str::limit(strip_tags((string) ($page->excerpt ?: $page->content)), 200),
                    'content' => (string) ($page->content ?? ''),
                    'url' => route('pages.show', ['slug' => $page->slug]),
                    'image_url' => '',
                    'site_name' => $fallback['site_name'],
                ];
            }
        }

        if ($type === 'url') {
            $url = trim((string) ($this->data['mail_template_source_url'] ?? ''));
            if ($url !== '') {
                try {
                    $response = Http::timeout(8)->get($url);
                    if ($response->successful()) {
                        $html = (string) $response->body();
                        $title = '';
                        if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches) === 1) {
                            $title = trim(strip_tags($matches[1]));
                        }
                        $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($html))), 200);

                        return [
                            'title' => $title ?: $fallback['title'],
                            'excerpt' => $excerpt ?: $fallback['excerpt'],
                            'content' => $html,
                            'url' => $url,
                            'image_url' => '',
                            'site_name' => $fallback['site_name'],
                        ];
                    }
                } catch (\Throwable) {
                    // fallback below
                }
            }
        }

        return $fallback;
    }

    protected function renderMailTemplate(array $payload): string
    {
        $template = (string) ($this->data['mail_template_html'] ?? '');
        if ($template === '') {
            $template = '<h2>{{title}}</h2><p>{{excerpt}}</p><p><a href="{{url}}">Xem chi tiết</a></p>';
        }

        $replace = [];
        foreach ($payload as $key => $value) {
            $replace['{{' . $key . '}}'] = (string) $value;
        }

        return strtr($template, $replace);
    }
}


