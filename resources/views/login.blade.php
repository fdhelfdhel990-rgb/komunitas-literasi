{{-- INI VIEW DUMMY BUAT NGETES FITUR AJA, NTAR GANTI/BIKIN BARU --}}

<form action="/login" method="POST">
    @csrf
    @error('login_id')
        <div style="color: red;">{{ $message }}</div>
    @enderror

    <label>Email atau Username:</label>
    <input type="text" name="login_id" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>