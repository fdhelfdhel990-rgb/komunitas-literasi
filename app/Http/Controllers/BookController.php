<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Genre;

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
                  ->orWhereHas('author', function($q) use ($keyword) {
                      $q->where('name', 'LIKE', "%{$keyword}%");
                  });
        }

        if ($request->has('genre') && $request->query('genre') !== '') {
            $query->whereHas('genre', function($q) use ($request) {
                $q->where('name', $request->query('genre'));
            });
        }

        if ($request->has('publisher') && $request->query('publisher') !== '') {
            $query->whereHas('publisher', function($q) use ($request) {
                $q->where('name', $request->query('publisher'));
            });
        }

        if ($request->query('sort') === 'za') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('title', 'asc');
        }

        $genres = \App\Models\Genre::pluck('name'); 
        $publishers = \App\Models\Publisher::pluck('name');

        $paginatedBooks = $query->paginate(6)->withQueryString(); 

        return view('library.index', [
            'paginatedBooks' => $paginatedBooks,
            'genres' => $genres,
            'publishers' => $publishers,
            'selectedBook' => null
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'genre'])->latest()->get();
        
        return view('admin.digital-library.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('add-book'); // PLACEHOLDER: ganti saat FE sudah bikin view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $judul = $request->title;
        $generated_slug = Str::slug($judul);

        $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'publisher_name' => 'required|string|max:255',
            'genre_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $author = Author::firstOrCreate(['name' => $request->author_name]);
        $publisher = Publisher::firstOrCreate(['name' => $request->publisher_name]);
        $genre = Genre::firstOrCreate(['name' => $request->genre_name]);

        Book::create([
            'title' => $request->title,
            'slug' => $generated_slug,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id' => $genre->id,
            'description' => $request->description,
            'created_by' => 1, // DUMMY: isi 1 dulu karena belum ada sistem login fiks
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
