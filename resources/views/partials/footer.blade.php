<footer class="bg-zinc-50 border-t border-zinc-200 pt-20 pb-10 dark:bg-zinc-950 dark:border-zinc-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Company Info -->
            <div class="space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-zinc-900 rounded flex items-center justify-center text-white font-bold dark:bg-white dark:text-zinc-900">
                        T
                    </div>
                    <span class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white">TechSewing</span>
                </a>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed">
                    Chuyên cung cấp các giải pháp máy may công nghiệp hàng đầu, hỗ trợ doanh nghiệp nâng cao năng suất và chất lượng sản phẩm.
                </p>
                <div class="flex gap-4">
                    {{-- Social Icons --}}
                    <a href="#" class="w-10 h-10 rounded-full bg-white border border-zinc-200 flex items-center justify-center text-zinc-600 hover:bg-zinc-900 hover:text-white transition-all dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-zinc-900 dark:text-white font-bold mb-6">Khám phá</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Trang chủ</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Sản phẩm</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Tin tức</a></li>
                    <li><a href="{{ route('about') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Về chúng tôi</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-zinc-900 dark:text-white font-bold mb-6">Hỗ trợ</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('contact') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Liên hệ</a></li>
                    <li><a href="#" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-sm">Điều khoản dịch vụ</a></li>
                </ul>
            </div>

            <!-- Contact Details -->
            <div>
                <h4 class="text-zinc-900 dark:text-white font-bold mb-6">Liên hệ</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-zinc-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-zinc-600 dark:text-zinc-400 text-sm">123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="text-zinc-600 dark:text-zinc-400 text-sm">0123 456 789</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-zinc-600 dark:text-zinc-400 text-sm">info@techsewing.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="pt-10 border-t border-zinc-200 dark:border-zinc-900 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-zinc-500 dark:text-zinc-500 text-xs">
                &copy; {{ date('Y') }} TechSewing. All rights reserved.
            </p>
            <div class="flex gap-8">
                <span class="text-zinc-500 dark:text-zinc-500 text-xs uppercase tracking-widest font-semibold">Premium Design</span>
                <span class="text-zinc-500 dark:text-zinc-500 text-xs uppercase tracking-widest font-semibold">Laravel & Tailwind</span>
            </div>
        </div>
    </div>
</footer>
