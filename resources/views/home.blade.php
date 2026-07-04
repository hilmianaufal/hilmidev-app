<x-app-layout>
<section class="relative overflow-hidden bg-gradient-to-br from-sky-50 via-white to-blue-100">
    <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-300/30 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 py-14"
         x-data="{
            search: '',
            category: 'all'
         }">

        <div class="text-center max-w-4xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 bg-white/80 border border-blue-100 backdrop-blur-xl px-4 py-2 rounded-full shadow-xl shadow-blue-500/10 mb-5">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span class="text-sm font-extrabold text-blue-700">
                    Source Code Marketplace
                </span>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-2xl border border-blue-100 rounded-[2rem] p-4 md:p-5 shadow-2xl shadow-blue-500/10 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-blue-500"></i>

                    <input x-model="search"
                           type="text"
                           placeholder="Cari source code..."
                           class="w-full pl-12 pr-4 py-4 rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500 font-bold">
                </div>

                <div class="lg:col-span-2 flex gap-2 overflow-x-auto pb-1">
                    <button type="button"
                            @click="category = 'all'"
                            :class="category === 'all' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-blue-50 text-blue-700'"
                            class="shrink-0 px-5 py-4 rounded-2xl font-black transition">
                        Semua
                    </button>

                    @foreach ($categories as $cat)
                        <button type="button"
                                @click="category = '{{ $cat->slug }}'"
                                :class="category === '{{ $cat->slug }}' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-blue-50 text-blue-700'"
                                class="shrink-0 px-5 py-4 rounded-2xl font-black transition">
                            {{ $cat->icon }} {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($featuredProducts as $product)
                <a href="{{ route('products.show', $product) }}"
                   x-show="
                        ('{{ strtolower($product->name) }}'.includes(search.toLowerCase()) ||
                         '{{ strtolower($product->short_description) }}'.includes(search.toLowerCase()) ||
                         '{{ strtolower($product->technology ?? '') }}'.includes(search.toLowerCase()))
                        &&
                        (category === 'all' || category === '{{ $product->category->slug ?? '' }}')
                   "
                   x-transition
                   class="group bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition duration-300">

                    <div class="aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 overflow-hidden">
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-400">
                                <i data-lucide="package" class="w-14 h-14"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-black">
                                {{ $product->category->icon ?? '📦' }} {{ $product->category->name ?? 'Source Code' }}
                            </span>

                            <span class="text-xs font-black text-slate-400">
                                {{ $product->technology ?? 'Laravel' }}
                            </span>
                        </div>

                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-3 line-clamp-2">
                            {{ $product->short_description }}
                        </p>

                        <div class="flex justify-between items-end mt-6 pt-5 border-t border-blue-50">
                            <div>
                                <p class="text-xs text-slate-400 font-bold">Harga</p>
                                <p class="text-2xl font-black text-blue-600">
                                    Rp {{ number_format($product->final_price ?? $product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white border border-blue-100 rounded-[2rem] p-12 text-center">
                    <p class="font-black text-slate-900">Belum ada source code.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-7 py-4 rounded-2xl font-black shadow-xl shadow-blue-500/30">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Lihat Semua Source Code
            </a>
        </div>
    </div>
</section>

    <section class="premium-section max-w-7xl mx-auto px-4 py-16">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 text-blue-600 font-black mb-3">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
                Why HilmiDev
            </div>

            <h2 class="text-3xl md:text-5xl font-black tracking-tight">
                Dibangun untuk bisnis yang ingin terlihat mahal.
            </h2>

            <p class="text-slate-500 mt-4">
                Desain premium, performa cepat, mobile-first, dan sistem siap dikembangkan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="feature-card bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-5">
                    <i data-lucide="smartphone" class="w-7 h-7"></i>
                </div>
                <h3 class="font-black text-xl">Mobile First</h3>
                <p class="text-sm text-slate-500 mt-2">Tampilan nyaman untuk HP, tablet, dan desktop.</p>
            </div>

            <div class="feature-card bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-14 h-14 rounded-2xl bg-cyan-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="paintbrush" class="w-7 h-7"></i>
                </div>
                <h3 class="font-black text-xl">Premium UI</h3>
                <p class="text-sm text-slate-500 mt-2">Visual clean, modern, dan cocok untuk brand profesional.</p>
            </div>

            <div class="feature-card bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="shield-check" class="w-7 h-7"></i>
                </div>
                <h3 class="font-black text-xl">Secure</h3>
                <p class="text-sm text-slate-500 mt-2">Download private dan flow pembayaran lebih aman.</p>
            </div>

            <div class="feature-card bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="rocket" class="w-7 h-7"></i>
                </div>
                <h3 class="font-black text-xl">Scalable</h3>
                <p class="text-sm text-slate-500 mt-2">Siap dikembangkan ke payment gateway dan realtime.</p>
            </div>
        </div>
    </section>

    <section class="premium-section max-w-7xl mx-auto px-4 py-16">
        <div class="flex items-end justify-between gap-5 mb-8">
            <div>
                <p class="text-blue-600 font-black mb-2">Marketplace</p>
                <h2 class="text-3xl md:text-5xl font-black">Source Code Premium</h2>
            </div>

            <a href="{{ route('products.index') }}" class="hidden sm:inline-flex text-blue-600 font-black">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($featuredProducts as $product)
                <a href="{{ route('products.show', $product) }}"
                   class="product-card group bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition">
                    <div class="aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 overflow-hidden">
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-400">
                                <i data-lucide="image" class="w-12 h-12"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-black">
                            {{ $product->category->name ?? 'Source Code' }}
                        </span>

                        <h3 class="mt-4 text-xl font-black group-hover:text-blue-600">
                            {{ $product->name }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-2 line-clamp-2">
                            {{ $product->short_description }}
                        </p>

                        <div class="flex justify-between items-end mt-6 pt-5 border-t border-blue-50">
                            <div>
                                <p class="text-xs text-slate-400 font-bold">Harga</p>
                                <p class="text-2xl font-black text-blue-600">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white border border-blue-100 rounded-[2rem] p-10 text-center">
                    <p class="font-bold text-slate-500">Belum ada produk featured.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="premium-section bg-gradient-to-br from-blue-50 via-white to-cyan-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-end justify-between gap-5 mb-8">
                <div>
                    <p class="text-blue-600 font-black mb-2">Services</p>
                    <h2 class="text-3xl md:text-5xl font-black">Jasa Website Premium</h2>
                </div>

                <a href="{{ route('services.index') }}" class="hidden sm:inline-flex text-blue-600 font-black">
                    Lihat Semua →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse ($featuredServices as $service)
                    <a href="{{ route('services.show', $service) }}"
                       class="service-card group bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition">
                        <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center text-3xl mb-5">
                            {{ $service->icon ?? '💼' }}
                        </div>

                        <h3 class="text-xl font-black group-hover:text-blue-600">
                            {{ $service->title }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-3 line-clamp-2">
                            {{ $service->short_description }}
                        </p>

                        <div class="mt-6 pt-5 border-t border-blue-50 flex justify-between items-end">
                            <div>
                                <p class="text-xs text-slate-400 font-bold">Mulai dari</p>
                                <p class="text-2xl font-black text-blue-600">
                                    Rp {{ number_format($service->starting_price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-white border border-blue-100 rounded-[2rem] p-10 text-center">
                        <p class="font-bold text-slate-500">Belum ada jasa featured.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="premium-section py-20 bg-gradient-to-br from-slate-50 via-white to-blue-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">

        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-full font-black mb-4">
                <i data-lucide="message-square-heart" class="w-4 h-4"></i>
                Testimonial
            </div>

            <h2 class="text-4xl md:text-6xl font-black">
                Apa Kata Client?
            </h2>

            <p class="text-slate-500 mt-4 max-w-2xl mx-auto">
                Kepercayaan client adalah aset terbesar kami.
            </p>
        </div>

        <div class="testimonial-slider flex gap-6">
            @foreach($testimonials as $testimonial)
                <div class="testimonial-card min-w-[380px] bg-white border border-blue-100 rounded-[2rem] p-8 shadow-xl shadow-blue-500/5">

                    <div class="flex items-center gap-1 mb-5 text-amber-400">
                        @for($i=1;$i<=5;$i++)
                            <i data-lucide="star"
                               class="w-5 h-5 {{ $i <= $testimonial->rating ? 'fill-current' : '' }}">
                            </i>
                        @endfor
                    </div>

                    <p class="text-slate-600 leading-relaxed mb-8">
                        "{{ $testimonial->review }}"
                    </p>

                    <div class="flex items-center gap-4">
                        @if($testimonial->photo)
                            <img src="{{ asset('storage/'.$testimonial->photo) }}"
                                 class="w-14 h-14 rounded-2xl object-cover">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i data-lucide="user" class="w-6 h-6"></i>
                            </div>
                        @endif

                        <div>
                            <h4 class="font-black">
                                {{ $testimonial->name }}
                            </h4>

                            <p class="text-sm text-slate-500">
                                {{ $testimonial->position }}
                                @if($testimonial->company)
                                    • {{ $testimonial->company }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

    <section class="premium-section max-w-7xl mx-auto px-4 py-16">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-slate-950 via-blue-950 to-blue-700 p-8 md:p-14 text-white shadow-2xl shadow-blue-500/30">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl"></div>

            <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="font-black text-cyan-300 mb-3">Project Request</p>
                    <h2 class="text-3xl md:text-5xl font-black leading-tight">
                        Punya ide project? Kirim requirement sekarang.
                    </h2>
                    <p class="text-blue-100 mt-5">
                        Pilih layanan, isi kebutuhan project, lalu admin HilmiDev akan review dan memberi estimasi.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row lg:justify-end gap-3">
                    <a href="{{ route('services.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-7 py-4 rounded-2xl font-black">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        Request Project
                    </a>

                    <a href="https://wa.me/6281234567890"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 bg-blue-500 text-white px-7 py-4 rounded-2xl font-black">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <a href="https://wa.me/6281234567890"
       target="_blank"
       class="fixed bottom-5 right-5 z-50 w-16 h-16 rounded-full bg-green-500 text-white flex items-center justify-center shadow-2xl shadow-green-500/40 hover:scale-110 transition">
        <i data-lucide="message-circle" class="w-8 h-8"></i>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            if (typeof gsap !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                gsap.from('.hero-copy > *', {
                    y: 50,
                    opacity: 0,
                    duration: 1,
                    stagger: 0.12,
                    ease: 'power3.out'
                });

                gsap.from('.hero-visual', {
                    y: 80,
                    opacity: 0,
                    rotateX: 12,
                    rotateY: -12,
                    duration: 1.2,
                    ease: 'power3.out'
                });

                gsap.to('.dashboard-3d', {
                    y: -18,
                    rotateY: 5,
                    rotateX: -4,
                    duration: 3,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                gsap.to('.floating-badge', {
                    y: -20,
                    duration: 2.5,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                gsap.to('.floating-badge-2', {
                    y: 20,
                    duration: 2.8,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                gsap.to('.floating-orb', {
                    x: 40,
                    y: -30,
                    duration: 5,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                gsap.to('.floating-orb-2', {
                    x: -40,
                    y: 30,
                    duration: 6,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                gsap.utils.toArray('.premium-section').forEach((section) => {
                    gsap.from(section, {
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 82%'
                        },
                        y: 70,
                        opacity: 0,
                        duration: 0.9,
                        ease: 'power3.out'
                    });
                });

                gsap.utils.toArray('.product-card, .service-card, .feature-card').forEach((card) => {
                    card.addEventListener('mousemove', function (e) {
                        const rect = card.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;

                        const rotateY = ((x / rect.width) - 0.5) * 10;
                        const rotateX = ((y / rect.height) - 0.5) * -10;

                        gsap.to(card, {
                            rotateX: rotateX,
                            rotateY: rotateY,
                            transformPerspective: 1000,
                            duration: 0.3,
                            ease: 'power2.out'
                        });
                    });

                    card.addEventListener('mouseleave', function () {
                        gsap.to(card, {
                            rotateX: 0,
                            rotateY: 0,
                            duration: 0.6,
                            ease: 'elastic.out(1, 0.4)'
                        });
                    });
                });

                gsap.utils.toArray('.counter').forEach((counter) => {
                    const target = parseInt(counter.dataset.count);

                    gsap.fromTo(counter,
                        { innerText: 0 },
                        {
                            innerText: target,
                            duration: 2,
                            ease: 'power2.out',
                            snap: { innerText: 1 },
                            scrollTrigger: {
                                trigger: counter,
                                start: 'top 90%'
                            }
                        }
                    );
                });

                document.addEventListener('mousemove', function (e) {
                    const x = (e.clientX / window.innerWidth - 0.5) * 20;
                    const y = (e.clientY / window.innerHeight - 0.5) * 20;

                    gsap.to('.dashboard-3d', {
                        x: x,
                        y: y,
                        duration: 0.8,
                        ease: 'power2.out'
                    });

                    gsap.to('.floating-badge', {
                        x: x * -0.6,
                        y: y * -0.6,
                        duration: 0.8,
                        ease: 'power2.out'
                    });

                    gsap.to('.floating-badge-2', {
                        x: x * 0.6,
                        y: y * 0.6,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                });

                if(document.querySelector('.testimonial-slider'))
                {
                    gsap.to('.testimonial-slider', {
                        x: -1000,
                        duration: 30,
                        repeat: -1,
                        ease: 'none'
                    });
                }
            }
        });
    </script>
</x-app-layout>
