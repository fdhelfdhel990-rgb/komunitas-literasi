<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Workspace') - Komunitas Literasi Remaja Tambun Selatan</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] text-slate-800 font-['Poppins',sans-serif] antialiased min-h-screen flex flex-col lg:flex-row overflow-x-hidden">

    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" aria-hidden="true"></div>

    <!-- Sidebar -->
    <aside 
        id="sidebar" 
        class="fixed inset-y-0 left-0 z-50 w-64 max-w-[80vw] bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none"
    >
        <div class="p-5 sm:p-6 overflow-y-auto">
            <!-- Brand Logo & Mobile Close Button -->
            <div class="mb-7 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="inline-block" aria-label="Dashboard">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Komunitas Literasi Remaja Tambun Selatan" class="w-28 sm:w-32 h-auto object-contain">
                </a>
                <button 
                    id="sidebar-close-btn" 
                    type="button" 
                    class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 lg:hidden focus:outline-none" 
                    aria-label="Tutup menu navigasi"
                >
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- 01 UMUM -->
            <div class="mb-6">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-2.5">
                    Umum
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#E8EEF8] text-[#0A2373]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.dashboard') ? 'text-[#0A2373]' : 'text-slate-400' }}">01</span>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <!-- MANAJEMEN KONTEN -->
            <div class="mb-6">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-2.5">
                    Manajemen Konten
                </span>
                <nav class="space-y-1 text-sm font-medium">
                    <a 
                        href="{{ route('admin.publications.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.publications.*') ? 'bg-[#E8EEF8] text-[#0A2373] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.publications.*') ? 'text-[#0A2373]' : 'text-slate-400' }}">02</span>
                        <span>Publication</span>
                    </a>
                    <a 
                        href="{{ route('admin.library.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.library.*') ? 'bg-[#E8EEF8] text-[#0A2373] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.library.*') ? 'text-[#0A2373]' : 'text-slate-400' }}">03</span>
                        <span>Digital Library</span>
                    </a>
                    <a 
                        href="{{ route('admin.join-us.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.join-us.*') ? 'bg-[#E8EEF8] text-[#0A2373] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.join-us.*') ? 'text-[#0A2373]' : 'text-slate-400' }}">04</span>
                        <span>Join Us</span>
                    </a>
                    <a 
                        href="{{ route('admin.media-partners.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.media-partners.*') ? 'bg-[#E8EEF8] text-[#0A2373] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.media-partners.*') ? 'text-[#0A2373]' : 'text-slate-400' }}">05</span>
                        <span>Partnership</span>
                    </a>
                    <a 
                        href="{{ route('admin.settings.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#E8EEF8] text-[#0A2373] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.settings.*') ? 'text-[#0A2373]' : 'text-slate-400' }}">06</span>
                        <span>Identitas & Konten</span>
                    </a>
                </nav>
            </div>

            <!-- PENGATURAN AKSES -->
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-2.5">
                    Pengaturan Akses
                </span>
                <nav class="space-y-1 text-sm font-medium">
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-[#E8EEF8] text-[#0A2373] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <span class="text-xs font-['Poppins'] font-bold {{ request()->routeIs('admin.users.*') ? 'text-[#0A2373]' : 'text-slate-400' }}">07</span>
                        <span>Kelola Admin & Izin</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Bottom Sidebar Link -->
        <div class="p-5 sm:p-6 border-t border-slate-100">
            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 text-sm font-semibold text-[#0A2373] hover:text-[#071a57]">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Lihat website publik</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30">
            <!-- Left: Toggle & Breadcrumb -->
            <div class="flex items-center gap-3 min-w-0">
                <button 
                    id="sidebar-toggle-btn" 
                    type="button" 
                    class="p-2 -ml-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 lg:hidden focus:outline-none" 
                    aria-label="Buka menu sidebar"
                >
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="text-xs sm:text-sm font-medium text-slate-500 flex items-center gap-1.5 sm:gap-2 truncate">
                    <span class="hidden xs:inline">Workspace</span>
                    <span class="text-slate-300 hidden xs:inline">/</span>
                    <span class="text-slate-800 font-semibold truncate">@yield('page_title', 'Dashboard')</span>
                </div>
            </div>

            <!-- Right: User info & Logout -->
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#E8EEF8] text-[#0A2373] font-bold flex items-center justify-center text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-left leading-tight hidden sm:block">
                        <div class="text-sm font-semibold text-slate-900 max-w-[120px] md:max-w-[180px] truncate">{{ Auth::user()?->name ?? 'Admin Literasi' }}</div>
                        <div class="text-xs text-slate-400 capitalize">{{ (Auth::user()?->role === 'super_admin') ? 'Super admin' : 'Admin' }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 sm:px-3.5 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 active:scale-[0.98] transition">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Drawer Interaction Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');

            function openSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                }, 10);
                document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            }

            function closeSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
                document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Close on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                    closeSidebar();
                }
            });

            // Auto-close on resize to large screen
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024 && backdrop) {
                    backdrop.classList.add('hidden', 'opacity-0');
                    backdrop.classList.remove('opacity-100');
                    document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
