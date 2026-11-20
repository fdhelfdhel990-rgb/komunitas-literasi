@extends('layouts.app')

@section('title', 'Partnership - Komunitas Literasi Remaja Tambun Selatan')

@section('content')
<main class="partnership-page py-10 sm:py-16">
    <div class="container max-w-5xl mx-auto px-4 sm:px-6">
        <!-- Page Header -->
        <div class="mb-10 sm:mb-14">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight font-['Poppins']">
                Partnership
            </h1>
            <p class="text-slate-500 text-sm sm:text-base mt-2.5 max-w-2xl font-normal">
                Kolaborasi yang mendukung gerakan literasi di Tambun Selatan.
            </p>
        </div>

        <!-- Section: Logo Media Partner -->
        <section class="mb-12 sm:mb-16">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-6 font-['Poppins']">
                Media Partner
            </h2>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 sm:gap-8 items-center justify-items-center">
                    @forelse ($partners as $partner)
                        <div class="w-full h-20 sm:h-24 flex items-center justify-center p-2 hover:scale-105 transition-transform duration-200" title="{{ $partner['name'] }}">
                            <img 
                                src="{{ asset('assets/images/partners/' . $partner['logo']) }}" 
                                alt="{{ $partner['name'] }}" 
                                class="max-h-16 max-w-[180px] w-auto h-auto object-contain"
                                loading="lazy"
                            >
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-400 text-sm">
                            Belum ada logo media partner.
                        </div>
                    @endforelse
                </div>

                @if (method_exists($partners, 'lastPage') && $partners->lastPage() > 1)
                    <nav class="flex items-center justify-center gap-2 mt-8 pt-6 border-t border-slate-100" aria-label="Navigasi halaman media partner">
                        @if ($partners->currentPage() > 1)
                            <a href="{{ $partners->previousPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-700 hover:border-[#0A2373] hover:text-[#0A2373] hover:bg-slate-50 transition shadow-2xs" aria-label="Halaman sebelumnya">
                                <i class="fa-solid fa-chevron-left text-sm"></i>
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-100 text-slate-300 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-sm"></i>
                            </span>
                        @endif

                        @for ($i = 1; $i <= $partners->lastPage(); $i++)
                            @if ($i === $partners->currentPage())
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#0A2373] text-sm font-bold text-white shadow-xs">{{ $i }}</span>
                            @else
                                <a href="{{ $partners->url($i) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:border-[#0A2373] hover:text-[#0A2373] hover:bg-slate-50 transition shadow-2xs">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($partners->hasMorePages())
                            <a href="{{ $partners->nextPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-700 hover:border-[#0A2373] hover:text-[#0A2373] hover:bg-slate-50 transition shadow-2xs" aria-label="Halaman berikutnya">
                                <i class="fa-solid fa-chevron-right text-sm"></i>
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-100 text-slate-300 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right text-sm"></i>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        </section>

        <!-- Section: Publikasi & Kabar Kegiatan Kemitraan -->
        <section class="mb-14">
            <div class="mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight font-['Poppins']">
                    Publikasi &amp; Kabar Kegiatan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Dokumentasi perjalanan, artikel edukatif, dan liputan berita komunitas bersama mitra.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($partnershipArticles as $pub)
                    <a href="{{ route('publications.show', $pub->slug) }}" class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md hover:border-[#0A2373]/30 transition duration-300 flex flex-col">
                        <div class="aspect-16/10 overflow-hidden bg-slate-100">
                            <img 
                                src="{{ \Illuminate\Support\Str::startsWith($pub->featured_image_path, ['http://', 'https://']) ? $pub->featured_image_path : ($pub->featured_image_path ? asset('assets/images/publications/'.$pub->featured_image_path) : 'https://placehold.co/600x400?text=No+Image') }}" 
                                alt="{{ $pub->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-[#0A2373]">
                                    {{ $pub->category ?? 'Partnership' }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($pub->published_at)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#0A2373] transition line-clamp-2 mb-2 font-['Poppins']">
                                {{ $pub->title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed mt-auto">
                                {{ $pub->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($pub->content), 120) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200/80">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                            <i class="fa-regular fa-newspaper text-xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 mb-1 font-['Poppins']">Belum Ada Publikasi Kemitraan</h4>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                            Kabar kolaborasi dan kegiatan kemitraan bersama mitra literasi akan segera hadir di sini.
                        </p>
                    </div>
                @endforelse
            </div>

            @if (method_exists($partnershipArticles, 'lastPage') && $partnershipArticles->lastPage() > 1)
                <nav class="flex items-center justify-center gap-2 mt-8 pt-6" aria-label="Navigasi halaman publikasi kemitraan">
                    @if ($partnershipArticles->currentPage() > 1)
                        <a href="{{ $partnershipArticles->previousPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-700 hover:border-[#0A2373] hover:text-[#0A2373] hover:bg-slate-50 transition shadow-2xs" aria-label="Halaman sebelumnya">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-100 text-slate-300 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </span>
                    @endif

                    @for ($i = 1; $i <= $partnershipArticles->lastPage(); $i++)
                        @if ($i === $partnershipArticles->currentPage())
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#0A2373] text-sm font-bold text-white shadow-xs">{{ $i }}</span>
                        @else
                            <a href="{{ $partnershipArticles->url($i) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:border-[#0A2373] hover:text-[#0A2373] hover:bg-slate-50 transition shadow-2xs">{{ $i }}</a>
                        @endif
                    @endfor

                    @if ($partnershipArticles->hasMorePages())
                        <a href="{{ $partnershipArticles->nextPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-700 hover:border-[#0A2373] hover:text-[#0A2373] hover:bg-slate-50 transition shadow-2xs" aria-label="Halaman berikutnya">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-100 text-slate-300 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </span>
                    @endif
                </nav>
            @endif
        </section>

        <!-- Call to Action Kemitraan -->
        <section class="bg-[#0A2373] text-white rounded-2xl sm:rounded-3xl p-8 sm:p-12 text-center shadow-lg">
            <h3 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white mb-3 font-['Poppins']">
                Tertarik Menjadi Mitra Kolaborasi Literasi?
            </h3>
            <p class="text-xs sm:text-sm text-slate-200 max-w-xl mx-auto mb-6 leading-relaxed">
                Kami membuka pintu selebar-lebarnya bagi lembaga, institusi pendidikan, BUMN, korporasi swasta, maupun komunitas untuk bersama memajukan literasi generasi penerus bangsa.
            </p>
            <div class="inline-flex flex-wrap justify-center gap-3">
                <a 
                    href="{{ route('join-us') }}" 
                    class="inline-flex items-center justify-center px-6 py-3 sm:px-8 sm:py-3.5 rounded-xl bg-white text-[#0A2373] text-sm sm:text-base font-bold tracking-tight hover:bg-slate-100 active:scale-[0.98] transition-all duration-200 shadow-md font-['Poppins']"
                    style="color: #0A2373;"
                >
                    Ajukan Kerja Sama Kolaborasi
                </a>
            </div>
        </section>
    </div>
</main>
@endsection
