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
    public function indexPublic()
    {
        $buku_publik = Book::where('is_published', true)->get();
        
        return view('library.index', compact('buku_publik'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('add_book'); // PLACEHOLDER: ganti saat FE sudah bikin view
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
