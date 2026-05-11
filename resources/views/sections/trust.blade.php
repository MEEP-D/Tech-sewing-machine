<section class="py-32 bg-zinc-900 overflow-hidden relative">
    {{-- Decorative engineering background --}}
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        <div class="absolute left-0 right-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-24">
            <h2 class="text-3xl md:text-5xl font-bold text-white tracking-tighter mb-8">Tin tưởng bởi các tập đoàn hàng đầu</h2>
            <div class="flex flex-wrap justify-center gap-12 md:gap-24 opacity-40 grayscale hover:grayscale-0 transition-all duration-700">
                {{-- Mock logos --}}
                @foreach(['JUKI', 'BROTHER', 'JACK', 'SINGER', 'SIRUBA'] as $brand)
                    <span class="text-2xl md:text-4xl font-black text-white tracking-tighter opacity-50">{{ $brand }}</span>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="p-10 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-sm group hover:bg-white/10 transition-all">
                <span class="block text-5xl font-black text-white mb-4 tabular-nums">500+</span>
                <h4 class="text-lg font-bold text-zinc-300 mb-4">Dự án hoàn thành</h4>
                <p class="text-sm text-zinc-500 leading-relaxed">Cung cấp giải pháp cho các xưởng may quy mô lớn trên khắp 63 tỉnh thành Việt Nam.</p>
            </div>
            <div class="p-10 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-sm group hover:bg-white/10 transition-all">
                <span class="block text-5xl font-black text-white mb-4 tabular-nums">15k+</span>
                <h4 class="text-lg font-bold text-zinc-300 mb-4">Thiết bị vận hành</h4>
                <p class="text-sm text-zinc-500 leading-relaxed">Số lượng máy móc chúng tôi đã lắp đặt và đang hỗ trợ kỹ thuật định kỳ hàng tháng.</p>
            </div>
            <div class="p-10 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-sm group hover:bg-white/10 transition-all">
                <span class="block text-5xl font-black text-white mb-4 tabular-nums">98%</span>
                <h4 class="text-lg font-bold text-zinc-300 mb-4">Hài lòng từ khách hàng</h4>
                <p class="text-sm text-zinc-500 leading-relaxed">Tỉ lệ khách hàng tiếp tục sử dụng dịch vụ và nâng cấp thiết bị cùng TechSewing.</p>
            </div>
        </div>
    </div>
</section>
