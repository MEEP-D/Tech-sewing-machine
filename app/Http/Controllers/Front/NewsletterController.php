<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request, NewsletterService $newsletterService): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ], [
            'email.required' => 'Vui long nhap email.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        $newsletterService->subscribe($validated['email']);

        return back()->with('newsletter_success', 'Da gui email xac nhan. Vui long kiem tra hop thu de kich hoat dang ky.');
    }

    public function confirm(string $token, NewsletterService $newsletterService): RedirectResponse
    {
        $subscriber = $newsletterService->confirm($token);

        if (! $subscriber) {
            return redirect()->route('home')->with('newsletter_error', 'Link xác nhận không hợp lệ hoặc đã hết hạn.');
        }

        return redirect()->route('home')->with('newsletter_success', 'Đăng ký nhận tin thành công.');
    }

    public function unsubscribe(NewsletterSubscriber $subscriber, string $hash, NewsletterService $newsletterService): RedirectResponse
    {
        if (! hash_equals(sha1($subscriber->email), $hash)) {
            abort(403);
        }

        $newsletterService->unsubscribe($subscriber);

        return redirect()->route('home')->with('newsletter_success', 'Ban da huy dang ky nhan ban tin.');
    }
}
