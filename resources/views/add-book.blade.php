<h1>Formulir Tambah Buku (Input Manual)</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="/buku" method="POST">
    @csrf
    <label>Judul Buku:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Nama Penulis:</label><br>
    <input type="text" name="author_name" required><br><br>

    <label>Nama Penerbit:</label><br>
    <input type="text" name="publisher_name" required><br><br>

    <label>Genre:</label><br>
    <input type="text" name="genre_name" required><br><br>

    <button type="submit">Simpan Buku</button>
</form>
