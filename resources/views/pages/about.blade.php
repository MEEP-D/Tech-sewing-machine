@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="pt-40 pb-24 bg-zinc-900 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <span class="text-xs font-bold uppercase tracking-[0.3em] text-zinc-400 mb-6 block">Về chúng tôi</span>
                <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight leading-tight mb-8">
                    Kiến tạo tương lai cho ngành may mặc.
                </h1>
                <p class="text-xl text-zinc-400 leading-relaxed font-medium">
                    TechSewing không chỉ cung cấp máy móc, chúng tôi cung cấp giải pháp vận hành tối ưu, giúp doanh nghiệp bứt phá về năng suất và chất lượng.
                </p>
            </div>
        </div>
        <div class="absolute right-0 top-0 w-1/3 h-full bg-gradient-to-l from-white/5 to-transparent hidden lg:block"></div>
    </section>

    {{-- Story Section --}}
    <section class="py-32 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                <div class="space-y-8">
                    <h2 class="text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">Câu chuyện thương hiệu</h2>
                    <div class="prose prose-zinc dark:prose-invert max-w-none prose-p:text-lg prose-p:leading-relaxed text-zinc-600 dark:text-zinc-400">
                        <p>Được thành lập với khát vọng số hóa và tự động hóa quy trình sản xuất may mặc, TechSewing đã đồng hành cùng hàng trăm xưởng sản xuất từ quy mô nhỏ đến các tập đoàn dệt may lớn.</p>
                        <p>Chúng tôi tin rằng, sự kết hợp giữa kỹ thuật tinh xảo và công nghệ tiên tiến là chìa khóa để tạo ra những sản phẩm hoàn hảo. Đội ngũ chuyên gia của chúng tôi luôn không ngừng tìm kiếm và thử nghiệm những giải pháp mới nhất để mang đến giá trị thực sự cho khách hàng.</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-12 pt-8">
                        <div>
                            <span class="block text-4xl font-black text-zinc-900 dark:text-white mb-2">10+</span>
                            <span class="text-xs font-bold uppercase tracking-widest text-zinc-400">Năm kinh nghiệm</span>
                        </div>
                        <div>
                            <span class="block text-4xl font-black text-zinc-900 dark:text-white mb-2">500+</span>
                            <span class="text-xs font-bold uppercase tracking-widest text-zinc-400">Khách hàng tin tưởng</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('assets/frontend/images/placeholder.jpg') }}" alt="TechSewing Team" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-zinc-50 dark:bg-zinc-900 rounded-full -z-10 animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Core Values --}}
    <section class="py-32 bg-zinc-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-20">
            <h2 class="text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">Giá trị cốt lõi</h2>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-12">
            @php
                $values = [
                    ['title' => 'Chất lượng hàng đầu', 'desc' => 'Mỗi thiết bị chúng tôi cung cấp đều trải qua quy trình kiểm tra nghiêm ngặt.', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                    ['title' => 'Tận tâm hỗ trợ', 'desc' => 'Dịch vụ kỹ thuật 24/7, luôn sẵn sàng giải quyết mọi vấn đề phát sinh.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['title' => 'Đổi mới sáng tạo', 'desc' => 'Cập nhật những công nghệ máy may tiên tiến nhất trên thế giới.', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z']
                ];
            @endphp
            @foreach($values as $v)
                <div class="bg-white dark:bg-zinc-950 p-10 rounded-3xl border border-zinc-100 dark:border-zinc-800 hover:shadow-xl transition-all group">
                    <div class="w-16 h-16 bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $v['icon'] }}"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-4">{{ $v['title'] }}</h3>
                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $v['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-32 bg-white dark:bg-zinc-950">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight mb-8">Bạn đã sẵn sàng để nâng tầm quy trình sản xuất?</h2>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-10 py-5 bg-zinc-900 text-white rounded-full font-bold text-lg dark:bg-white dark:text-zinc-900 hover:opacity-90 transition-all">
                Liên hệ với chúng tôi ngay
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>
@endsection
