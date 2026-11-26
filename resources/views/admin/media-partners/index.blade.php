@extends('layouts.admin')

@section('title', 'Kelola Partnership')
@section('page_title', 'Partnership')

@section('content')
    @php
        $canManagePartners = Auth::user()->canManage('media_partners');
    @endphp

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs">
            <div class="font-semibold mb-1.5 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 ml-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Mitra Kolaborasi &amp; Partnership
            </h1>
            @unless ($canManagePartners)
                <p class="text-xs text-slate-500 mt-1">Mode lihat saja (Anda tidak memiliki izin untuk menambah, mengubah, atau menghapus data ini).</p>
            @endunless
        </div>

        @if ($canManagePartners)
            <div>
                <a 
                    href="{{ route('admin.media-partners.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Logo Mitra</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Partner Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @forelse ($partners as $partner)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="w-full h-24 rounded-xl bg-slate-50 border border-slate-100 p-3 flex items-center justify-center mb-3 overflow-hidden">
                        <img 
                            src="{{ asset('assets/images/partners/'.$partner['logo']) }}" 
                            alt="{{ $partner['name'] }}" 
                            class="max-h-16 max-w-full object-contain"
                        >
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 truncate mb-1" title="{{ $partner['name'] }}">
                        {{ $partner['name'] }}
                    </h3>
                </div>

                @if ($canManagePartners)
                    <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-end">
                        <div class="flex items-center gap-1">
                            <a 
                                href="{{ route('admin.media-partners.edit', $partner['id']) }}" 
                                class="p-1.5 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                                title="Edit logo mitra"
                            >
                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                            </a>
                            <form action="{{ route('admin.media-partners.destroy', $partner['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus logo mitra {{ addslashes($partner['name']) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus mitra">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200/90 p-8 text-center text-slate-400">
                Belum ada logo media partner.
            </div>
        @endforelse
    </div>
@endsection
