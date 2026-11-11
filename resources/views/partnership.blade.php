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
                Logo Media Partner
            </h2>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 sm:gap-8 items-center justify-items-center">
                    @foreach ($partners as $partner)
                        <div class="w-full h-20 sm:h-24 flex items-center justify-center p-2 hover:scale-105 transition-transform duration-200" title="{{ $partner['name'] }}">
                            <img 
                                src="{{ asset('assets/images/partners/' . $partner['logo']) }}" 
                                alt="{{ $partner['name'] }}" 
                                class="max-h-16 max-w-[180px] w-auto h-auto object-contain"
                                loading="lazy"
                            >
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Section: Profil Mitra -->
        <section class="space-y-5 mb-14">
            @foreach ($profiles as $profile)
                <article class="bg-[#F8FAFC] rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-2xs hover:border-[#0A2373]/30 transition">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2 font-['Poppins']">
                        {{ $profile['tag'] }}
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 font-['Poppins']">
                        {{ $profile['name'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-3">
                        {{ $profile['description'] }}
                    </p>
                    <div class="text-xs sm:text-sm text-slate-500 font-medium">
                        {{ $profile['support'] }}
                    </div>
                </article>
            @endforeach
        </section>

        <!-- Section: Dokumentasi Kerja Sama -->
        <section class="mb-14">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-5 font-['Poppins']">
                DOKUMENTASI KERJA SAMA · DATA CONTOH
            </span>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs group">
                    <div class="aspect-4/3 overflow-hidden bg-slate-100">
                        <img 
                            src="{{ asset('assets/images/publications/publication-1.jpg') }}" 
                            alt="Dokumentasi Penyaluran Buku" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                    <div class="p-4 sm:p-5">
                        <h4 class="text-sm font-bold text-slate-900 mb-1 group-hover:text-[#0A2373] transition">
                            Penyaluran Buku Bacaan Bermutu
                        </h4>
                        <p class="text-xs text-slate-500 line-clamp-2">
                            Pemberian paket buku bergambar dan literasi anak bersama mitra korporasi.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs group">
                    <div class="aspect-4/3 overflow-hidden bg-slate-100">
                        <img 
                            src="{{ asset('assets/images/publications/publication-2.jpg') }}" 
                            alt="Dokumentasi Kelas Membaca Bersama" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                    <div class="p-4 sm:p-5">
                        <h4 class="text-sm font-bold text-slate-900 mb-1 group-hover:text-[#0A2373] transition">
                            Kelas Membaca &amp; Mendongeng
                        </h4>
                        <p class="text-xs text-slate-500 line-clamp-2">
                            Aktivitas interaktif relawan dan perwakilan mitra mengajar anak-anak di perpustakaan desa.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs group">
                    <div class="aspect-4/3 overflow-hidden bg-slate-100">
                        <img 
                            src="{{ asset('assets/images/publications/publication-3.jpg') }}" 
                            alt="Dokumentasi Gerobak Angkasa Keliling" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                    <div class="p-4 sm:p-5">
                        <h4 class="text-sm font-bold text-slate-900 mb-1 group-hover:text-[#0A2373] transition">
                            Armada Gerobak Angkasa Bergerak
                        </h4>
                        <p class="text-xs text-slate-500 line-clamp-2">
                            Peresmian perluasan rute operasional perpustakaan keliling menjangkau pelosok desa.
                        </p>
                    </div>
                </div>
            </div>
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
