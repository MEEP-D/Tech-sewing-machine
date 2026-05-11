@extends('layouts.app')

@section('content')
    {{-- Header Section --}}
    <section class="pt-32 pb-20 bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white tracking-tight mb-6">
                    Hãy kết nối với chúng tôi.
                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium">
                    Dù bạn đang tìm kiếm giải pháp cho xưởng sản xuất mới hay muốn nâng cấp hệ thống hiện tại, đội ngũ chuyên gia của TechSewing luôn sẵn sàng lắng nghe.
                </p>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                
                <!-- Contact Form -->
                <div>
                    @if(session('success'))
                        <div class="mb-10 p-6 bg-green-50 border border-green-100 rounded-3xl flex items-center gap-4 text-green-800 animate-bounce">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="font-bold">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 ml-1">Họ và tên</label>
                                <input type="text" name="name" required class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white transition-all text-zinc-900 dark:text-white" placeholder="Nguyễn Văn A">
                                @error('name') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 ml-1">Số điện thoại</label>
                                <input type="text" name="phone" required class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white transition-all text-zinc-900 dark:text-white" placeholder="0123 456 789">
                                @error('phone') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 ml-1">Email</label>
                                <input type="email" name="email" class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white transition-all text-zinc-900 dark:text-white" placeholder="email@vi-du.com">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 ml-1">Tên công ty (nếu có)</label>
                                <input type="text" name="company" class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white transition-all text-zinc-900 dark:text-white" placeholder="TechSewing Vietnam">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 ml-1">Vấn đề bạn quan tâm</label>
                            <select name="interest" class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white transition-all text-zinc-900 dark:text-white appearance-none">
                                <option value="general">Tư vấn chung</option>
                                <option value="product">Yêu cầu báo giá sản phẩm</option>
                                <option value="technical">Hỗ trợ kỹ thuật</option>
                                <option value="partnership">Hợp tác kinh doanh</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 ml-1">Nội dung tin nhắn</label>
                            <textarea name="message" rows="6" required class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white transition-all text-zinc-900 dark:text-white resize-none" placeholder="Tôi cần tư vấn về dòng máy..."></textarea>
                            @error('message') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center gap-3 px-10 py-5 bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 rounded-2xl font-bold text-lg hover:opacity-90 transition-all shadow-xl shadow-zinc-900/10">
                            Gửi yêu cầu ngay
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Info Sidebar -->
                <div class="lg:pl-20 space-y-16">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-8">Thông tin liên hệ</h4>
                        <div class="space-y-10">
                            <div class="flex items-start gap-6">
                                <div class="w-12 h-12 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl flex items-center justify-center text-zinc-900 dark:text-white flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-zinc-900 dark:text-white mb-1">Văn phòng đại diện</h5>
                                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-6">
                                <div class="w-12 h-12 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl flex items-center justify-center text-zinc-900 dark:text-white flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-zinc-900 dark:text-white mb-1">Hỗ trợ khách hàng</h5>
                                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">info@techsewing.com<br>support@techsewing.com</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-6">
                                <div class="w-12 h-12 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl flex items-center justify-center text-zinc-900 dark:text-white flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-zinc-900 dark:text-white mb-1">Giờ làm việc</h5>
                                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">Thứ 2 - Thứ 7: 08:00 - 17:30<br>Chủ nhật: Nghỉ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-900 rounded-3xl p-10 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="text-2xl font-bold mb-4">Bạn là nhà cung cấp?</h4>
                            <p class="text-zinc-400 mb-8 leading-relaxed">Chúng tôi luôn tìm kiếm những đối tác chiến lược để mở rộng danh mục sản phẩm cao cấp.</p>
                            <a href="mailto:partner@techsewing.com" class="text-sm font-bold uppercase tracking-widest underline decoration-2 underline-offset-8 hover:text-zinc-300 transition-all">Gửi đề xuất hợp tác</a>
                        </div>
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
