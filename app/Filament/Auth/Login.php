<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected string $view = 'filament.auth.login';

    public function getMaxWidth(): Width | string | null
    {
        return Width::ScreenExtraLarge;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Đăng nhập quản trị';
    }

    public function getHeading(): string | Htmlable | null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return 'Xác minh danh tính';
        }

        return 'Chào mừng Admin trở lại';
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return 'Nhập mã xác minh để tiếp tục truy cập khu vực quản trị.';
        }

        return 'Đăng nhập để quản lý sản phẩm, tin tức, khách hàng và cấu hình website Tech Sewing Machine.';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email quản trị')
            ->placeholder('admin@techsewing.vn')
            ->helperText('Dùng email đã được cấp quyền quản trị.')
            ->prefixIcon('heroicon-m-envelope')
            ->email()
            ->required()
            ->validationMessages([
                'required' => 'Vui lòng nhập email quản trị.',
                'email' => 'Email quản trị chưa đúng định dạng. Ví dụ: admin@techsewing.vn.',
            ])
            ->autocomplete('email')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Mật khẩu')
            ->placeholder('Nhập mật khẩu bảo mật')
            ->helperText('Không chia sẻ mật khẩu. Hãy dùng mật khẩu mạnh để bảo vệ dữ liệu website.')
            ->prefixIcon('heroicon-m-lock-closed')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->validationMessages([
                'required' => 'Vui lòng nhập mật khẩu.',
            ]);
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ghi nhớ đăng nhập trên thiết bị này');
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'Email hoặc mật khẩu chưa chính xác. Vui lòng kiểm tra lại thông tin đăng nhập.',
        ]);
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Đăng nhập vào quản trị')
            ->icon('heroicon-m-arrow-right-end-on-rectangle')
            ->submit('authenticate');
    }
}
