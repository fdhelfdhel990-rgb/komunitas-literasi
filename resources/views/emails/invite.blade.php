{{-- INI VIEW EMAIL DUMMY BUAT NGETES FITUR AJA, NTAR GANTI/BIKIN BARU --}}

<!DOCTYPE html>
<html>
<head>
    <title>Undangan Admin</title>
</head>
<body>
    <h2>Halo, {{ $nama }}!</h2>
    <p>Anda telah didaftarkan menjadi Admin di Website Komunitas Literasi.</p>
    <p>Berikut adalah detail akun Anda untuk login:</p>
    <ul>
        <li><strong>Username:</strong> {{ $username }}</li>
        <li><strong>Password:</strong> {{ $passwordPlain }}</li>
    </ul>
    <p>Silakan login melalui tautan berikut: <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>
    <p>Terima kasih!</p>
</body>
</html>
