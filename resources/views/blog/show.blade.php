<x-app-layout>
    <article>
        <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-500 to-cyan-400">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative max-w-5xl mx-auto px-4 py-20 text-white">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-6">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Blog
                </a>

                <div class="flex flex-wrap items-center gap-4 text-blue-100 text-sm font-bold mb-5">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        {{ $post->published_at?->format('d M Y') }}
                    </div>

                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        {{ $post->author->name ?? 'HilmiDev' }}
                    </div>
                </div>

                <h1 class="text-4xl md:text-6xl font-black leading-tight">
                    {{ $post->title }}
                </h1>

                <p class="text-blue-50 text-lg mt-6 max-w-3xl leading-relaxed">
                    {{ $post->excerpt }}
                </p>
            </div>
        </section>

        <section class="max-w-5xl mx-auto px-4 py-12">
            @if ($post->thumbnail)
                <div class="overflow-hidden rounded-[2rem] shadow-2xl shadow-blue-500/10 mb-10">
                    <img src="{{ asset('storage/' . $post->thumbnail) }}"
                         class="w-full object-cover"
                         alt="{{ $post->title }}">
                </div>
            @endif

            <div class="bg-white border border-blue-100 rounded-[2rem] p-8 md:p-12 shadow-xl shadow-blue-500/5">
                <div class="prose prose-lg max-w-none prose-blue">
                    {!! $post->content !!}
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 pb-20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-slate-900">
                        Artikel Terkait
                    </h2>

                    <p class="text-slate-500 mt-2">
                        Artikel lain yang mungkin menarik untuk dibaca.
                    </p>
                </div>

                <a href="{{ route('blog.index') }}"
                   class="hidden md:inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-2xl font-black shadow-lg shadow-blue-500/30">
                    Semua Artikel
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related) }}"
                       class="group bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/15 transition">
                        <div class="aspect-video bg-blue-50 overflow-hidden">
                            @if ($related->thumbnail)
                                <img src="{{ asset('storage/' . $related->thumbnail) }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                     alt="{{ $related->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-blue-400">
                                    <i data-lucide="newspaper" class="w-12 h-12"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            <h3 class="font-black text-slate-900 line-clamp-2 group-hover:text-blue-600 transition">
                                {{ $related->title }}
                            </h3>

                            <p class="text-sm text-slate-500 mt-3 line-clamp-3">
                                {{ $related->excerpt }}
                            </p>

                            <div class="flex items-center justify-between mt-5">
                                <span class="text-xs text-slate-400 font-bold">
                                    {{ $related->published_at?->format('d M Y') }}
                                </span>

                                <i data-lucide="arrow-up-right"
                                   class="w-5 h-5 text-blue-600"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 pb-20">
            <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-400 rounded-[2rem] p-10 text-white text-center shadow-2xl shadow-blue-500/20">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-3xl md:text-5xl font-black">
                        Butuh Website atau Aplikasi Custom?
                    </h2>

                    <p class="text-blue-50 mt-5 text-lg">
                        HilmiDev siap membantu membuat website company profile,
                        dashboard admin, aplikasi bisnis, hingga sistem informasi
                        berbasis Laravel.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                        <a href="{{ route('services.index') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-8 py-4 rounded-2xl font-black shadow-xl">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                            Request Project
                        </a>

                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center justify-center gap-2 bg-blue-900/20 border border-white/20 backdrop-blur-xl px-8 py-4 rounded-2xl font-black">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            Lihat Source Code
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </article>
</x-app-layout>