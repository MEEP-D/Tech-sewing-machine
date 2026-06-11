<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class DynamicMailConfigService
{
    public function apply(): void
    {
        $mailer = $this->settingOrConfig('mail_mailer', 'mail.default', 'smtp');
        $host = $this->settingOrConfig('mail_host', 'mail.mailers.smtp.host', '');

        if ($mailer === '') {
            return;
        }

        if ($mailer === 'smtp' && $host === '') {
            return;
        }

        $password = $this->settingOrConfig('mail_password', 'mail.mailers.smtp.password', '');
        $password = $this->resolvePassword($password);
        $encryption = $this->settingOrConfig('mail_encryption', 'mail.mailers.smtp.scheme', 'tls');

        config([
            'mail.default' => $mailer,
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => (int) $this->settingOrConfig('mail_port', 'mail.mailers.smtp.port', 587),
            'mail.mailers.smtp.scheme' => $encryption ?: null,
            'mail.mailers.smtp.encryption' => $encryption ?: null,
            'mail.mailers.smtp.username' => $this->settingOrConfig('mail_username', 'mail.mailers.smtp.username', ''),
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => $this->settingOrConfig('mail_from_address', 'mail.from.address', ''),
            'mail.from.name' => $this->settingOrConfig('mail_from_name', 'mail.from.name', config('app.name')),
        ]);

        Mail::purge($mailer);
    }

    private function settingOrConfig(string $settingKey, string $configKey, mixed $default = null): string
    {
        $value = Setting::getValue($settingKey, null);

        if (filled($value)) {
            return (string) $value;
        }

        $value = config($configKey, $default);

        return filled($value) ? (string) $value : '';
    }

    private function resolvePassword(string $value): string
    {
        if ($value === '') {
            return '';
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }
}
