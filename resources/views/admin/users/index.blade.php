@extends('layouts.admin')

@section('title', 'Kelola Admin & Izin')
@section('page_title', 'Kelola Admin & Izin')

@section('content')
    @php
        $activeList = $users->filter(fn ($u) => $u->status === 'aktif' && ! $u->trashed())->values();
        $inactiveList = $users->filter(fn ($u) => $u->status === 'tidak aktif' || $u->trashed())->values();
        $eligibleTransferAdmins = $activeList->filter(fn ($u) => ! $u->isSuperAdmin() && $u->id !== Auth::id())->values();
    @endphp

    <!-- Flash Notifications -->
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

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs">
            <div class="font-semibold mb-1.5 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span>Terjadi kesalahan validasi:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-rose-700 ml-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Kelola Admin &amp; Hak Akses
            </h1>
        </div>
        <div>
            <a 
                href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer whitespace-nowrap"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Admin Baru</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Total Akun Admin</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $totalUsers ?? $users->count() }}</div>
        </div>
        <button type="button" onclick="switchAdminTab('active')" class="text-left bg-white rounded-2xl border border-slate-200/90 hover:border-[#0A2373]/40 p-4 sm:p-5 shadow-xs transition cursor-pointer">
            <div class="text-xs text-slate-500 font-medium mb-1">Akun Aktif</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $activeUsers ?? $activeList->count() }}</div>
        </button>
        <button type="button" onclick="switchAdminTab('inactive')" class="text-left bg-white rounded-2xl border border-slate-200/90 hover:border-rose-900/40 p-4 sm:p-5 shadow-xs transition cursor-pointer">
            <div class="text-xs text-slate-500 font-medium mb-1">Akun Dinonaktifkan</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $inactiveUsers ?? $inactiveList->count() }}</div>
        </button>
    </div>

    <!-- Segmented Tab Switcher -->
    <div class="mb-5">
        <div class="inline-grid grid-cols-2 w-full sm:w-auto sm:min-w-[340px] rounded-xl border border-slate-200 bg-white p-1.5 shadow-2xs" role="tablist">
            <button
                type="button"
                id="tab-btn-active"
                role="tab"
                aria-selected="true"
                onclick="switchAdminTab('active')"
                class="flex items-center justify-center px-5 py-4 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer bg-[#E8EEF8] text-[#0A2373] border-b-2 border-[#0A2373]"
            >
                Admin Aktif
            </button>

            <button
                type="button"
                id="tab-btn-inactive"
                role="tab"
                aria-selected="false"
                onclick="switchAdminTab('inactive')"
                class="flex items-center justify-center px-5 py-4 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer text-slate-500 hover:text-slate-800"
            >
                Admin Nonaktif
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TABEL 1: ADMIN AKTIF                                           -->
    <!-- ============================================================== -->
    <div id="panel-admin-active" class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5 w-12 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama &amp; Email</th>
                        <th class="px-4 sm:px-6 py-3.5">Username</th>
                        <th class="px-4 sm:px-6 py-3.5">Peran</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($activeList as $index => $u)
                        @php
                            $isSuper = $u->isSuperAdmin();
                            $isCurrentUser = Auth::id() === $u->id;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-semibold text-slate-900 flex items-center gap-2">
                                    <span>{{ $u->name }}</span>
                                    @if ($isCurrentUser)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-normal">(Anda)</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-600 font-['Poppins'] text-xs">
                                {{ $u->username ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $isSuper ? 'bg-[#E8EEF8] text-[#0A2373]' : 'bg-slate-100 text-slate-700' }} text-xs font-bold">
                                    {{ $isSuper ? 'Super Admin' : 'Admin' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 whitespace-nowrap">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                                    Aktif
                                </span>
                            </td>
                            <!-- Action Column (Aktif: Edit & Nonaktifkan) -->
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a 
                                        href="{{ route('admin.users.edit', $u->id) }}" 
                                        title="Edit Profil &amp; Hak Akses"
                                        class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:text-[#0A2373] hover:border-[#0A2373] hover:bg-[#0A2373]/5 transition cursor-pointer"
                                    >
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    @if (! $isCurrentUser && ! $isSuper)
                                        <form action="{{ route('admin.users.deactivate', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan akun admin {{ addslashes($u->name) }}? Akun akan dipindahkan ke daftar Admin Nonaktif dan tidak bisa login.')">
                                            @csrf
                                            @method('PATCH')
                                            <button 
                                                type="submit" 
                                                title="Nonaktifkan Akun"
                                                class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-rose-900/10 hover:text-rose-900 hover:border-rose-900/30 transition cursor-pointer"
                                            >
                                                <i class="fa-solid fa-power-off text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs sm:text-sm">
                                Tidak ada akun admin yang berstatus aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TABEL 2: ADMIN NONAKTIF                                        -->
    <!-- ============================================================== -->
    <div id="panel-admin-inactive" class="hidden bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5 w-12 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama &amp; Email</th>
                        <th class="px-4 sm:px-6 py-3.5">Username</th>
                        <th class="px-4 sm:px-6 py-3.5">Peran</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($inactiveList as $index => $u)
                        <tr class="bg-slate-50/40 hover:bg-slate-50/80 transition">
                            <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-semibold text-slate-700">
                                    {{ $u->name }}
                                </div>
                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-500 font-['Poppins'] text-xs">
                                {{ $u->username ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold">
                                    Admin
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 whitespace-nowrap">
                                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300" aria-hidden="true"></span>
                                    Nonaktif
                                </span>
                            </td>
                            <!-- Action Column (Nonaktif: Aktifkan & Hapus Permanen) -->
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <form action="{{ route('admin.users.restore', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Aktifkan kembali akun admin {{ addslashes($u->name) }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            title="Aktifkan Akun"
                                            class="p-2 rounded-lg border border-slate-200 text-emerald-700 hover:bg-emerald-50 hover:border-emerald-300 transition cursor-pointer"
                                        >
                                            <i class="fa-solid fa-user-check text-xs"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.force-delete', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('HAPUS PERMANEN akun {{ addslashes($u->name) }}? Data akun akan hilang selamanya dan tidak dapat dikembalikan!')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            title="Hapus Akun Permanen"
                                            class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-rose-900/10 hover:text-rose-900 hover:border-rose-900/30 transition cursor-pointer"
                                        >
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs sm:text-sm">
                                Tidak ada akun admin yang dinonaktifkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TRANSFER SUPER ADMIN                                     -->
    <!-- ============================================================== -->
    <div class="mt-8 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
        <div class="mb-4">
            <h2 class="text-base sm:text-lg font-bold text-slate-900">
                Transfer Super Admin
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Pilih akun penerima dan verifikasi kepemilikan sebelum melanjutkan.
            </p>
        </div>

        <div class="mb-6 rounded-xl bg-amber-50/90 border border-amber-200/90 px-4 py-3 text-xs sm:text-sm text-amber-800">
            Perhatian: tindakan ini akan memindahkan seluruh hak super admin kepada akun yang dipilih.
        </div>

        <form id="transfer-super-admin-form" method="POST" onsubmit="return handleTransferSubmit(event)" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <div>
                    <label for="transfer-target-select" class="block text-xs font-semibold text-slate-800 mb-1.5">
                        Admin penerima
                    </label>
                    <select
                        id="transfer-target-select"
                        required
                        onchange="updateTransferFormAction()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm text-slate-700 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373] transition cursor-pointer"
                    >
                        <option value="">Pilih admin penerima</option>
                        @foreach ($eligibleTransferAdmins as $candidate)
                            <option
                                value="{{ $candidate->id }}"
                                data-url="{{ route('admin.users.transfer-super-admin', $candidate->id) }}"
                                data-name="{{ $candidate->name }}"
                            >
                                {{ $candidate->name }} ({{ $candidate->username ?? $candidate->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="transfer-password" class="block text-xs font-semibold text-slate-800 mb-1.5">
                        Kata sandi super admin
                    </label>
                    <input
                        type="password"
                        id="transfer-password"
                        name="password"
                        required
                        placeholder="Masukkan kata sandi"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373] transition"
                    >
                </div>

                <div>
                    <label for="transfer-confirmation" class="block text-xs font-semibold text-slate-800 mb-1.5">
                        Konfirmasi Transfer Super Admin
                    </label>
                    <input
                        type="text"
                        id="transfer-confirmation"
                        name="confirmation"
                        required
                        autocomplete="off"
                        placeholder="Ketik TRANSFER"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373] transition"
                    >
                </div>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    Transfer hanya dapat dilakukan oleh pemilik akun super admin yang aktif.
                </p>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#A32015] hover:bg-[#871910] active:scale-[0.98] text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer whitespace-nowrap self-end sm:self-auto"
                >
                    Transfer Kepemilikan
                </button>
            </div>
        </form>
    </div>

    <script>
        function switchAdminTab(tab) {
            const isActiveTab = tab === 'active';

            const panelActive = document.getElementById('panel-admin-active');
            const panelInactive = document.getElementById('panel-admin-inactive');
            const btnActive = document.getElementById('tab-btn-active');
            const btnInactive = document.getElementById('tab-btn-inactive');

            panelActive.classList.toggle('hidden', !isActiveTab);
            panelInactive.classList.toggle('hidden', isActiveTab);

            btnActive.setAttribute('aria-selected', isActiveTab ? 'true' : 'false');
            btnInactive.setAttribute('aria-selected', !isActiveTab ? 'true' : 'false');

            if (isActiveTab) {
                btnActive.className = 'flex items-center justify-center px-5 py-4 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer bg-[#E8EEF8] text-[#0A2373] border-b-2 border-[#0A2373]';
                btnInactive.className = 'flex items-center justify-center px-5 py-4 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer text-slate-500 hover:text-slate-800';
            } else {
                btnInactive.className = 'flex items-center justify-center px-5 py-4 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer bg-rose-900/10 text-rose-900 border-b-2 border-rose-800';
                btnActive.className = 'flex items-center justify-center px-5 py-4 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer text-slate-500 hover:text-slate-800';
            }
        }

        function updateTransferFormAction() {
            const select = document.getElementById('transfer-target-select');
            const form = document.getElementById('transfer-super-admin-form');
            const selectedOption = select.options[select.selectedIndex];

            form.action = selectedOption ? (selectedOption.getAttribute('data-url') || '') : '';
        }

        function handleTransferSubmit(event) {
            const select = document.getElementById('transfer-target-select');
            const confirmationInput = document.getElementById('transfer-confirmation');
            const selectedOption = select.options[select.selectedIndex];

            if (!select.value || !selectedOption.getAttribute('data-url')) {
                event.preventDefault();
                alert('Silakan pilih admin penerima terlebih dahulu.');
                select.focus();
                return false;
            }

            if (confirmationInput.value.trim() !== 'TRANSFER') {
                event.preventDefault();
                alert('Ketik kata TRANSFER dengan huruf kapital pada kolom konfirmasi.');
                confirmationInput.focus();
                return false;
            }

            const targetName = selectedOption.getAttribute('data-name');
            return confirm(`Apakah Anda yakin ingin memindahkan jabatan Super Admin ke "${targetName}"? Jabatan Anda akan otomatis turun menjadi Admin biasa.`);
        }
    </script>
@endsection