<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $leads = [
            [
                'name' => 'Nguyễn Văn A',
                'phone' => '0909123456',
                'email' => 'a@example.com',
                'company' => 'Xưởng May ABC',
                'interest' => 'Máy 1 kim / vắt sổ',
                'message' => 'Cần tư vấn cấu hình chuyền may áo thun 30 công nhân, ưu tiên bền và dễ bảo trì.',
                'source' => 'website_contact',
                'status' => 'new',
                'notes' => null,
            ],
            [
                'name' => 'Trần Thị B',
                'phone' => '0903000000',
                'email' => null,
                'company' => 'May Giá Công B',
                'interest' => 'Máy lập trình',
                'message' => 'Xin báo giá máy may lập trình Brother, cần demo tại xưởng.',
                'source' => 'website_contact',
                'status' => 'contacted',
                'notes' => 'Đã gọi tư vấn sơ bộ, hẹn demo tuần sau.',
            ],
        ];

        foreach ($leads as $lead) {
            Lead::firstOrCreate(
                ['phone' => $lead['phone'], 'message' => $lead['message']],
                $lead
            );
        }
    }
}

