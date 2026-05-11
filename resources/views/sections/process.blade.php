<section class="py-32 bg-white dark:bg-zinc-950 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-24">
            
            <!-- Left: Sticky Content -->
            <div class="lg:sticky lg:top-32 lg:h-fit space-y-8">
                <span class="text-xs font-bold uppercase tracking-[0.4em] text-zinc-400 block">Quy trình chuyên nghiệp</span>
                <h2 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white tracking-tighter leading-none">
                    Từ tư vấn đến vận hành hoàn hảo.
                </h2>
                <p class="text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-md">
                    Chúng tôi không chỉ bán máy. Chúng tôi thiết lập cả một hệ thống sản xuất bền vững cho doanh nghiệp của bạn.
                </p>
                
                <div class="pt-8 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        @for($i=1; $i<=4; $i++)
                            <div class="w-12 h-12 rounded-full border-2 border-white dark:border-zinc-950 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-400">
                                {{ $i }}
                            </div>
                        @endfor
                    </div>
                    <p class="text-sm font-bold text-zinc-900 dark:text-white">Hơn 500+ dự án đã triển khai</p>
                </div>
            </div>

            <!-- Right: Steps -->
            <div class="space-y-20">
                @php
                    $steps = [
                        [
                            'title' => 'Khảo sát & Tư vấn',
                            'desc' => 'Đội ngũ chuyên gia trực tiếp khảo sát mô hình sản xuất, phân tích loại vải và sản phẩm để tư vấn dòng máy phù hợp nhất.',
                            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
                        ],
                        [
                            'title' => 'Thiết kế dây chuyền',
                            'desc' => 'Sắp xếp máy móc theo luồng vận hành tối ưu, giảm thiểu thời gian chờ và tối đa hóa năng suất lao động.',
                            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
                        ],
                        [
                            'title' => 'Lắp đặt & Đào tạo',
                            'desc' => 'Vận chuyển tận nơi, lắp đặt chuẩn kỹ thuật và đào tạo công nhân vận hành máy thành thạo, an toàn.',
                            'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'
                        ],
                        [
                            'title' => 'Bảo trì trọn đời',
                            'desc' => 'Hệ thống nhắc lịch bảo trì định kỳ và hỗ trợ kỹ thuật khẩn cấp trong vòng 24h trên toàn quốc.',
                            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
                        ]
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="relative pl-16 group" x-data="{ revealed: false }" x-intersect="revealed = true">
                        @if($index < count($steps) - 1)
                            <div class="absolute left-6 top-12 bottom-[-80px] w-px bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 dark:group-hover:bg-white transition-colors"></div>
                        @endif
                        
                        <div class="absolute left-0 top-0 w-12 h-12 rounded-2xl bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-center text-zinc-900 dark:text-white group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-white dark:group-hover:text-zinc-900 transition-all duration-500 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"></path></svg>
                        </div>
                        
                        <div x-show="revealed" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">{{ $step['title'] }}</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
