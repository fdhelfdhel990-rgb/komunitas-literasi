<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher; 
use App\Models\Genre;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kumpulan_buku = Book::all(); 

        return view('katalog', compact('kumpulan_buku')); 
    }

    public function create()
    {
        // 1. Tarik semua data dari tabel master di MySQL
        $kumpulan_penulis = Author::all();
        $kumpulan_penerbit = Publisher::all();
        $kumpulan_genre = Genre::all();

        return view('tambah_buku', compact('kumpulan_penulis', 'kumpulan_penerbit', 'kumpulan_genre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
