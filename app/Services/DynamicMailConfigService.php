<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class DynamicMailConfigService
{
    public function apply(): void
    {
        $mailer = (string) Setting::getValue('mail_mailer', 'smtp');
        $host = (string) Setting::getValue('mail_host', '');

        if ($mailer === '' || $host === '') {
            return;
        }

        $password = $this->resolvePassword((string) Setting::getValue('mail_password', ''));

        config([
            'mail.default' => $mailer,
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => (int) Setting::getValue('mail_port', 587),
            'mail.mailers.smtp.encryption' => Setting::getValue('mail_encryption', 'tls') ?: null,
            'mail.mailers.smtp.username' => Setting::getValue('mail_username', ''),
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => Setting::getValue('mail_from_address', config('mail.from.address')),
            'mail.from.name' => Setting::getValue('mail_from_name', config('mail.from.name')),
        ]);
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
