<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Komunitas Literasi Remaja Tambun Selatan</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-password-field {
            position: relative;
            width: 100%;
        }

        .login-password-field input {
            padding-right: 2.75rem;
        }

        .login-password-field button {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            padding: 0;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
        }

        .login-password-field button:hover {
            color: #0A2373;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 font-['Poppins',sans-serif] min-h-screen flex items-center justify-center p-4 sm:p-6 py-8 sm:py-12 antialiased">

    <div class="w-full max-w-[360px] sm:max-w-[400px] bg-white rounded-2xl border border-slate-200/90 shadow-[0_4px_25px_rgb(0,0,0,0.05)] p-5 sm:p-8 transition-all">
        <!-- Title -->
        <h1 class="text-2xl sm:text-3xl font-bold text-center text-slate-900 mb-6 tracking-tight">Login</h1>

        <!-- Session Status (e.g. from registration or logout) -->
        @if (session('status'))
            <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm font-medium text-emerald-800 leading-relaxed">
                {{ session('status') }}
            </div>
        @endif

        <!-- General Error Alert -->
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs sm:text-sm text-rose-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email / Username -->
            <div>
                <label for="email" class="block text-xs sm:text-sm font-medium text-slate-800 mb-1.5">
                    Email
                </label>
                <input 
                    type="text" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    class="w-full px-3.5 py-2.5 sm:py-2.5 rounded-lg border @error('email') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:border-[#0A2373] focus:ring-[#0A2373]/20 @enderror bg-white text-slate-800 outline-none focus:ring-2 transition duration-150 text-base sm:text-sm"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs sm:text-sm font-medium text-slate-800 mb-1.5">
                    Password
                </label>
                <div class="login-password-field">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        autocomplete="current-password" 
                        class="w-full px-3.5 py-2.5 sm:py-2.5 rounded-lg border @error('password') border-rose-400 focus:ring-rose-200 @else border-slate-300 focus:border-[#0A2373] focus:ring-[#0A2373]/20 @enderror bg-white text-slate-800 outline-none focus:ring-2 transition duration-150 text-base sm:text-sm"
                    >
                    <button
                        type="button"
                        id="toggle-password-visibility"
                        aria-label="Tampilkan password"
                        title="Tampilkan password"
                        class="focus:outline-none"
                    >
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Ingat Saya -->
            <div class="flex items-center gap-2 pt-1 pb-1 min-h-[36px]">
                <input 
                    id="remember" 
                    type="checkbox" 
                    name="remember" 
                    class="w-4 h-4 rounded border-slate-300 text-[#0A2373] focus:ring-[#0A2373] cursor-pointer"
                >
                <label for="remember" class="text-xs sm:text-sm text-slate-700 select-none cursor-pointer">
                    Ingat saya
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-[#0A2373] hover:bg-[#071a57] active:scale-[0.99] transition duration-150 shadow-sm cursor-pointer text-center text-sm sm:text-base min-h-[44px]"
                >
                    Login
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('toggle-password-visibility').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeIcon = this.querySelector('i');
            const isPasswordHidden = passwordInput.type === 'password';

            passwordInput.type = isPasswordHidden ? 'text' : 'password';
            this.setAttribute('aria-label', isPasswordHidden ? 'Sembunyikan password' : 'Tampilkan password');
            this.setAttribute('title', isPasswordHidden ? 'Sembunyikan password' : 'Tampilkan password');
            eyeIcon.classList.toggle('fa-eye', ! isPasswordHidden);
            eyeIcon.classList.toggle('fa-eye-slash', isPasswordHidden);
        });
    </script>
</body>
</html>
