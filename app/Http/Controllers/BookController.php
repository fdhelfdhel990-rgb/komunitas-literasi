<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function indexPublic(Request $request)
    {
        $query = Book::with(['author', 'publisher', 'genre'])
            ->where('is_published', true);

        if ($request->filled('genre')) {
            $genreName = $request->query('genre');
            $query->whereHas('genre', function ($q) use ($genreName) {
                $q->where('name', $genreName);
            });
        }

        if ($request->filled('publisher')) {
            $publisherName = $request->query('publisher');
            $query->whereHas('publisher', function ($q) use ($publisherName) {
                $q->where('name', $publisherName);
            });
        }

        if ($request->filled('q')) {
            $keyword = $request->query('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('author', function ($sub) use ($keyword) {
                        $sub->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        $paginatedBooks = $query->latest()->paginate(6)->withQueryString();

        $selectedBook = null;
        if ($request->filled('book')) {
            $selectedBook = Book::with(['author', 'publisher', 'genre'])
                ->where('is_published', true)
                ->where('slug', $request->query('book'))
                ->first();
        }

        $genres = Genre::orderBy('name')->pluck('name');
        $publishers = Publisher::orderBy('name')->pluck('name');

        return view('library.index', compact(
            'paginatedBooks',
            'selectedBook',
            'genres',
            'publishers'
        ));
    }


    /**
     * Display a listing of the resource for the admin panel.
     */
    public function indexAdmin(Request $request)
    {
        $query = Book::with(['author', 'publisher', 'genre'])->latest();

        if ($request->filled('q')) {
            $keyword = $request->query('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('author', fn ($sub) => $sub->where('name', 'LIKE', "%{$keyword}%"))
                    ->orWhereHas('publisher', fn ($sub) => $sub->where('name', 'LIKE', "%{$keyword}%"));
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genre', fn ($q) => $q->where('name', $request->query('genre')));
        }

        if ($request->filled('publisher')) {
            $query->whereHas('publisher', fn ($q) => $q->where('name', $request->query('publisher')));
        }

        $genres = Genre::orderBy('name')->pluck('name');
        $publishers = Publisher::orderBy('name')->pluck('name');
        $books = $query->paginate(6)->withQueryString();

        return view('admin.library.index', compact('books', 'genres', 'publishers'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('admin.library.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors    = Author::orderBy('name')->pluck('name');
        $publishers = Publisher::orderBy('name')->pluck('name');
        $genres     = Genre::orderBy('name')->pluck('name');

        return view('admin.library.create', compact('authors', 'publishers', 'genres'));
    }

    /**
     * Show the dedicated form for creating a new book in admin.
     */
    public function createAdmin()
    {
        $genres = Genre::pluck('name')->toArray();
        $publishers = Publisher::pluck('name')->toArray();
        $authors = Author::pluck('name')->toArray();

        return view('admin.library.create', compact('genres', 'publishers', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'author_name'    => ['required', 'string', 'max:255'],
            'publisher_name' => ['required', 'string', 'max:255'],
            'genre_name'     => ['required', 'string', 'max:255'],
            'isbn'           => ['nullable', 'string', 'max:50', 'unique:books,isbn'],
            'description'    => ['nullable', 'string'],
            'cover_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $author    = Author::firstOrCreate(['name' => trim($validated['author_name'])]);
        $publisher = Publisher::firstOrCreate(['name' => trim($validated['publisher_name'])]);
        $genre     = Genre::firstOrCreate(['name' => trim($validated['genre_name'])]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $file      = $request->file('cover_image');
            $directory = public_path('assets/images/books');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $coverPath = $filename;
        }

        Book::create([
            'title'        => $validated['title'],
            'slug'         => $this->generateUniqueSlug($validated['title']),
            'author_id'    => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id'     => $genre->id,
            'isbn'         => $validated['isbn'] ?? null,
            'description'  => $validated['description'] ?? null,
            'cover_path'   => $coverPath,
            'is_published' => true,
            'created_by'   => Auth::id(),
        ]);

        return redirect()->route('admin.library.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with(['author', 'publisher', 'genre'])->findOrFail($id);

        return redirect()->route('library.index', ['book' => $book->title]);
    }

    /**
     * Show the dedicated form for editing the specified book in admin.
     */
    public function editAdmin(string $id)
    {
        $book = Book::with(['author', 'publisher', 'genre'])->findOrFail($id);
        $genres = Genre::pluck('name')->toArray();
        $publishers = Publisher::pluck('name')->toArray();
        $authors = Author::pluck('name')->toArray();

        return view('admin.library.edit', compact('book', 'genres', 'publishers', 'authors'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $book->load(['author', 'publisher', 'genre']);

        $authors    = Author::orderBy('name')->pluck('name');
        $publishers = Publisher::orderBy('name')->pluck('name');
        $genres     = Genre::orderBy('name')->pluck('name');

        return view('admin.library.edit', compact('book', 'authors', 'publishers', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'author_name'    => ['required', 'string', 'max:255'],
            'publisher_name' => ['required', 'string', 'max:255'],
            'genre_name'     => ['required', 'string', 'max:255'],
            'isbn'           => ['nullable', 'string', 'max:50', 'unique:books,isbn,' . $book->id],
            'description'    => ['nullable', 'string'],
            'cover_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_cover'   => ['nullable', 'in:0,1'],
        ]);

        $author    = Author::firstOrCreate(['name' => trim($validated['author_name'])]);
        $publisher = Publisher::firstOrCreate(['name' => trim($validated['publisher_name'])]);
        $genre     = Genre::firstOrCreate(['name' => trim($validated['genre_name'])]);

        $directory = public_path('assets/images/books');
        $coverPath = $book->cover_path;

        // Jika tombol "Hapus" sampul diklik di form edit
        if ($request->input('remove_cover') === '1' && !$request->hasFile('cover_image')) {
            if ($book->cover_path && file_exists($directory . '/' . $book->cover_path)) {
                @unlink($directory . '/' . $book->cover_path);
            }
            $coverPath = null;
        }

        // Jika mengunggah sampul baru
        if ($request->hasFile('cover_image')) {
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            if ($book->cover_path && file_exists($directory . '/' . $book->cover_path)) {
                @unlink($directory . '/' . $book->cover_path);
            }

            $file     = $request->file('cover_image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $coverPath = $filename;
        }

        $slug = $book->title !== $validated['title']
            ? $this->generateUniqueSlug($validated['title'], $book->id)
            : $book->slug;

        $book->update([
            'title'        => $validated['title'],
            'slug'         => $slug,
            'author_id'    => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id'     => $genre->id,
            'isbn'         => $validated['isbn'] ?? null,
            'description'  => $validated['description'] ?? null,
            'cover_path'   => $coverPath,
        ]);

        return redirect()->route('admin.library.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover_path && file_exists(public_path('assets/images/books/' . $book->cover_path))) {
            @unlink(public_path('assets/images/books/' . $book->cover_path));
        }

        $book->delete();

        return redirect()->route('admin.library.index')->with('success', 'Buku berhasil dihapus.');
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug     = $baseSlug;
        $counter  = 2;

        while (
            Book::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
