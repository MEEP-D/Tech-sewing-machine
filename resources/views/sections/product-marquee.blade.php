<section class="py-12 bg-zinc-50 dark:bg-zinc-900 overflow-hidden">
    <div class="space-y-6">
        {{-- Row 1: Left to Right --}}
        <div class="flex whitespace-nowrap animate-marquee">
            @php
                $items1 = ['Máy Khâu Công Nghiệp', 'Máy Vắt Sổ 2 Kim', 'Máy May Lập Trình', 'Máy Cắt Vải Tự Động', 'Máy Thêu Vi Tính'];
            @endphp
            @foreach(array_merge($items1, $items1) as $item)
                <div class="flex items-center gap-4 px-10">
                    <span class="text-4xl md:text-6xl font-black text-zinc-200 dark:text-zinc-800 tracking-tighter uppercase">{{ $item }}</span>
                    <div class="w-3 h-3 rounded-full bg-zinc-400"></div>
                </div>
            @endforeach
        </div>

        {{-- Row 2: Right to Left --}}
        <div class="flex whitespace-nowrap animate-marquee-reverse">
            @php
                $items2 = ['Máy In Chuyển Nhiệt', 'Máy Ép Keo', 'Máy Dò Kim', 'Máy Thổi Form', 'Hệ Thống Treo'];
            @endphp
            @foreach(array_merge($items2, $items2) as $item)
                <div class="flex items-center gap-4 px-10">
                    <span class="text-4xl md:text-6xl font-black text-zinc-900 dark:text-white tracking-tighter uppercase">{{ $item }}</span>
                    <div class="w-3 h-3 rounded-full bg-zinc-900 dark:bg-white"></div>
                </div>
            @endforeach
        </div>

        {{-- Row 3: Left to Right --}}
        <div class="flex whitespace-nowrap animate-marquee">
            @php
                $items3 = ['Linh Kiện Chính Hãng', 'Phụ Tùng Máy May', 'Kim May Groz-Beckert', 'Dầu Máy Công Nghiệp', 'Motor Tiết Kiệm Điện'];
            @endphp
            @foreach(array_merge($items3, $items3) as $item)
                <div class="flex items-center gap-4 px-10">
                    <span class="text-4xl md:text-6xl font-black text-zinc-200 dark:text-zinc-800 tracking-tighter uppercase">{{ $item }}</span>
                    <div class="w-3 h-3 rounded-full bg-zinc-400"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    @keyframes marquee-reverse {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }
    .animate-marquee {
        animation: marquee 40s linear infinite;
    }
    .animate-marquee-reverse {
        animation: marquee-reverse 40s linear infinite;
    }
</style>
