    @extends('layouts.admin')

    @section('title', 'Tambah Admin Baru')
    @section('page_title', 'Kelola Admin & Izin')

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

        <!-- Back Navigation & Header -->
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-3">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Kelola Admin</span>
            </a>

            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Tambah Admin Baru
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Buat akun administrator baru dan tentukan hak akses halaman kelola yang diberikan.
                </p>
            </div>
        </div>

        <!-- Main Full-Page Create Form -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 max-w-3xl">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Informasi Akun -->
                <div class="space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                        Informasi Akun
                    </h2>

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            value="{{ old('name') }}" 
                            placeholder="Contoh: Sarah Anindya"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]"
                        >
                    </div>

                    <!-- Username & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
                            <input 
                                type="text" 
                                name="username" 
                                value="{{ old('username') }}" 
                                placeholder="sarah_admin"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                required 
                                value="{{ old('email') }}" 
                                placeholder="sarah@literasitamsel.id"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]"
                            >
                        </div>
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="password" 
                            required 
                            minlength="6" 
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]"
                        >
                    </div>
                </div>

                <!-- Peran & Hak Akses Halaman -->
                <div class="space-y-4 pt-2">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                        Peran &amp; Hak Akses
                    </h2>

                    <!-- Dropdown Checklist Akses Halaman (1-6) -->
                    <div id="privileges-container" class="space-y-2">
                        <!-- Interactive Dropdown Checklist Trigger -->
                        <div class="relative" id="privilege-dropdown-wrapper">
                            <button 
                                type="button" 
                                id="privilege-dropdown-btn" 
                                aria-label="Pilih halaman yang dapat diakses"
                                onclick="togglePrivilegeDropdown()"
                                class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-left text-xs sm:text-sm hover:border-[#0A2373] focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                            >
                                <span id="privilege-summary-text" class="text-slate-700 font-medium truncate">Pilih halaman</span>
                                <i id="privilege-chevron" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 ml-2"></i>
                            </button>

                            <!-- Dropdown Panel with Checkboxes -->
                            <div 
                                id="privilege-dropdown-panel" 
                                class="hidden mt-2 p-3 bg-white border border-slate-200 rounded-xl shadow-lg space-y-2 z-20"
                            >
                                <div class="flex flex-col gap-1" id="privilege-checkboxes">
                                    @php
                                        $availablePages = [
                                            'publikasi'        => ['label' => '02. Publication'],
                                            'digital_library'  => ['label' => '03. Digital Library'],
                                            'join_us'          => ['label' => '04. Join Us'],
                                            'media_partners'   => ['label' => '05. Partnership'],
                                            'identitas_konten' => ['label' => '06. Identitas & Konten'],
                                        ];
                                    @endphp

                                    @foreach ($availablePages as $key => $page)
                                        <label class="flex items-center gap-2.5 p-2 rounded-lg border border-slate-100 hover:bg-slate-50 cursor-pointer transition">
                                            <input 
                                                type="checkbox" 
                                                name="privileges[]" 
                                                value="{{ $key }}" 
                                                class="privilege-checkbox mt-0.5 rounded text-[#0A2373] focus:ring-[#0A2373]"
                                                onchange="updatePrivilegeSummary()"
                                                checked
                                            >
                                            <span class="text-xs font-semibold text-slate-800">{{ $page['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
                    >
                        Simpan Akun Baru
                    </button>
                </div>
            </form>
        </div>

        <script>
            function togglePrivilegeDropdown() {
                const panel = document.getElementById('privilege-dropdown-panel');
                const chevron = document.getElementById('privilege-chevron');
                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                } else {
                    panel.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                }
            }

            function updatePrivilegeSummary() {
                const checkboxes = document.querySelectorAll('.privilege-checkbox:checked');
                const summaryText = document.getElementById('privilege-summary-text');
                const count = checkboxes.length;
                
                if (count === 0) {
                    summaryText.textContent = 'Pilih halaman';
                    summaryText.className = 'text-rose-600 font-medium truncate';
                } else {
                    summaryText.textContent = `${checkboxes.length} halaman dipilih`;
                    summaryText.className = 'text-slate-800 font-medium truncate';
                }
            }

            // Initialize state
            document.addEventListener('DOMContentLoaded', () => {
                updatePrivilegeSummary();
            });
        </script>
    @endsection
