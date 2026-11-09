<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function indexPublic(Request $request)
    {
        if ($request->has('book')) {
            $selectedBook = Book::with(['author', 'publisher', 'genre'])
                ->where('title', $request->query('book'))
                ->first();

            return view('library.index', compact('selectedBook'));
        }

        $query = Book::with(['author', 'publisher', 'genre'])->where('is_published', true);

        if ($request->has('q') && $request->query('q') !== '') {
            $keyword = $request->query('q');
            $query->where('title', 'LIKE', "%{$keyword}%")
                ->orWhereHas('author', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
        }

        if ($request->has('genre') && $request->query('genre') !== '') {
            $query->whereHas('genre', function ($q) use ($request) {
                $q->where('name', $request->query('genre'));
            });
        }

        if ($request->has('publisher') && $request->query('publisher') !== '') {
            $query->whereHas('publisher', function ($q) use ($request) {
                $q->where('name', $request->query('publisher'));
            });
        }

        if ($request->query('sort') === 'za') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('title', 'asc');
        }

        $genres = Genre::pluck('name');
        $publishers = Publisher::pluck('name');

        $paginatedBooks = $query->paginate(6)->withQueryString();

        return view('library.index', [
            'paginatedBooks' => $paginatedBooks,
            'genres' => $genres,
            'publishers' => $publishers,
            'selectedBook' => null,
        ]);
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
        return redirect()->route('admin.library.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'publisher_name' => 'required|string|max:255',
            'genre_name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'description' => 'nullable|string',
        ]);

        $author = Author::firstOrCreate(['name' => trim($request->author_name)]);
        $publisher = Publisher::firstOrCreate(['name' => trim($request->publisher_name)]);
        $genre = Genre::firstOrCreate(['name' => trim($request->genre_name)]);

        $coverPath = 'book-1.jpg';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'book_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('assets/images/books'), $filename);
            $coverPath = $filename;
        }

        Book::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.Str::random(4),
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id' => $genre->id,
            'cover_path' => $coverPath,
            'description' => $request->description,
            'is_published' => true,
            'created_by' => auth()->id() ?? 1,
        ]);

        return redirect()->route('admin.library.index')->with('success', 'Buku "'.$request->title.'" berhasil ditambahkan ke katalog!');
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.library.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'publisher_name' => 'required|string|max:255',
            'genre_name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'description' => 'nullable|string',
        ]);

        $author = Author::firstOrCreate(['name' => trim($request->author_name)]);
        $publisher = Publisher::firstOrCreate(['name' => trim($request->publisher_name)]);
        $genre = Genre::firstOrCreate(['name' => trim($request->genre_name)]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.Str::random(4),
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id' => $genre->id,
            'description' => $request->description,
        ];

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'book_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('assets/images/books'), $filename);
            $data['cover_path'] = $filename;
        }

        $book->update($data);

        return redirect()->route('admin.library.index')->with('success', 'Data buku "'.$book->title.'" berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $title = $book->title;
        $book->delete();

        return redirect()->route('admin.library.index')->with('success', 'Buku "'.$title.'" berhasil dihapus dari katalog!');
    }
}
