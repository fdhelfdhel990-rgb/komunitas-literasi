<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Publication;

class HomeController extends Controller
{
    public function index()
    {
        $latestPublications = Publication::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestBooks = Book::with(['author', 'genre'])
            ->where('is_published', true)
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('latestPublications', 'latestBooks'));
    }
}