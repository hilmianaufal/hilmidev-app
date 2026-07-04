<x-app-layout>
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-500 to-cyan-400">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-24 w-80 h-80 bg-cyan-200/30 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24 text-white">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full mb-6">
                    <i data-lucide="newspaper" class="w-4 h-4"></i>
                    <span class="text-sm font-black">HilmiDev Blog SEO</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black leading-tight">
                    Artikel Seputar Website, Laravel, Source Code & Bisnis Digital
                </h1>

                <p class="text-blue-50 text-lg mt-6 leading-relaxed">
                    Insight, tutorial, dan tips membangun website premium, aplikasi custom,
                    dashboard admin, dan produk digital modern.
                </p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-14">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 text-blue-600 font-black mb-2">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                    Latest Articles
                </div>

                <h2 class="text-3xl md:text-4xl font-black text-slate-900">
                    Artikel Terbaru
                </h2>

                <p class="text-slate-500 mt-2">
                    Konten SEO untuk membantu kamu memahami dunia web development.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($posts as $post)
                <a href="{{ route('blog.show', $post) }}"
                   class="group bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition duration-300">
                    <div class="aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 overflow-hidden">
                        @if ($post->thumbnail)
                            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                 alt="{{ $post->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-400">
                                <i data-lucide="newspaper" class="w-14 h-14"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-center gap-2 text-xs text-slate-400 font-bold mb-3">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                            {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                        </div>

                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition line-clamp-2">
                            {{ $post->title }}
                        </h3>

                        <p class="text-sm text-slate-500 line-clamp-3 mt-3">
                            {{ $post->excerpt }}
                        </p>

                        <div class="flex items-center justify-between mt-6 pt-5 border-t border-blue-50">
                            <div class="flex items-center gap-2 text-sm text-slate-500 font-bold">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                {{ $post->author->name ?? 'HilmiDev' }}
                            </div>

                            <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center group-hover:scale-110 transition shadow-lg shadow-blue-500/30">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white border border-blue-100 rounded-[2rem] p-12 text-center shadow-xl shadow-blue-500/5">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <i data-lucide="newspaper" class="w-8 h-8"></i>
                    </div>

                    <p class="font-black text-slate-900">Belum ada artikel.</p>
                    <p class="text-slate-500 text-sm mt-1">
                        Artikel blog yang sudah publish akan tampil di sini.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </section>
</x-app-layout>