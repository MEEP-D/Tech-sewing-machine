<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function about(SeoService $seoService): View
    {
        $seo = $seoService->defaults('Về chúng tôi', 'Tìm hiểu về TechSewing - Giải pháp máy may công nghiệp hàng đầu.');
        return view('front.pages.about', compact('seo'));
    }

    public function index(SeoService $seoService): View
    {
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_contact_title', 'Liên hệ TechSewing'),
            \App\Models\Setting::getValue('seo_contact_description', 'Nhận tư vấn giải pháp máy may công nghiệp, báo giá, demo và hỗ trợ kỹ thuật từ TechSewing.')
        );

        return view('front.pages.contact', compact('seo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'interest' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.email' => 'Email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung cần hỗ trợ.',
        ]);

        $lead = Lead::create([
            ...$validated,
            'source' => 'website_contact',
            'status' => 'new',
        ]);

        try {
            $adminEmail = \App\Models\Setting::getValue('contact_email', 'admin@techsewing.vn');
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\NewLeadNotification($lead));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send new lead email: ' . $e->getMessage());
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Yêu cầu đã được gửi thành công. TechSewing sẽ liên hệ với bạn trong thời gian sớm nhất.');
    }

}
