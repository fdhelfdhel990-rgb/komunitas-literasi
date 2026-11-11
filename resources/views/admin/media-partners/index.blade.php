@extends('layouts.admin')

@section('title', 'Kelola Partnership')
@section('page_title', 'Partnership')

@section('content')
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
        </div>
        <div>
            <button 
                type="button" 
                onclick="openModal('create-partner-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Logo Mitra</span>
            </button>
        </div>
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

                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-end">
                    <div class="flex items-center gap-1">
                        <button 
                            type="button" 
                            onclick="openEditPartnerModal({{ json_encode($partner) }})" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                            title="Edit logo mitra"
                        >
                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                        </button>
                        <form action="{{ route('admin.media-partners.destroy', $partner['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus logo mitra {{ addslashes($partner['name']) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus mitra">
                                <i class="fa-regular fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200/90 p-8 text-center text-slate-400">
                Belum ada logo media partner. Klik tombol "+ Tambah Logo Mitra" di atas untuk menambahkan.
            </div>
        @endforelse
    </div>

    <!-- Modal Form: Tambah Logo Mitra Baru -->
    <div id="create-partner-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-lg overflow-y-auto transform scale-95 transition-transform duration-200">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Logo Mitra</h3>
                </div>
                <button type="button" onclick="closeModal('create-partner-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form action="{{ route('admin.media-partners.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lembaga / Mitra <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Universitas Gadjah Mada" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload Gambar Logo <span class="text-rose-500">*</span></label>
                    <input type="file" name="logo_image" required accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Format: PNG transparan, JPG, WebP, atau SVG (maks. 3MB)</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('create-partner-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] transition cursor-pointer">
                        Simpan Logo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: Edit Logo Mitra -->
    <div id="edit-partner-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-lg overflow-y-auto transform scale-95 transition-transform duration-200">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Logo Mitra</h3>
                </div>
                <button type="button" onclick="closeModal('edit-partner-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form id="edit-partner-form" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lembaga / Mitra <span class="text-rose-500">*</span></label>
                    <input id="edit-partner-name" type="text" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ganti Gambar Logo (Opsional)</label>
                    <input type="file" name="logo_image" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar logo saat ini.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('edit-partner-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.remove('hidden');
        setTimeout(() => {
            m.classList.remove('opacity-0');
            m.firstElementChild?.classList.remove('scale-95');
            m.firstElementChild?.classList.add('scale-100');
        }, 10);
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.add('opacity-0');
        m.firstElementChild?.classList.remove('scale-100');
        m.firstElementChild?.classList.add('scale-95');
        setTimeout(() => m.classList.add('hidden'), 200);
    }

    function openEditPartnerModal(partner) {
        const form = document.getElementById('edit-partner-form');
        form.action = '{{ url('/admin/media-partners') }}/' + partner.id;
        document.getElementById('edit-partner-name').value = partner.name || '';
        openModal('edit-partner-modal');
    }
</script>
@endpush
