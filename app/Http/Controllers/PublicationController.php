<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publication::where('status', 'published');

        if ($request->filled('q')) {
            $keyword = $request->query('q');
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('excerpt', 'LIKE', "%{$keyword}%")
                  ->orWhere('category', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $categories = Publication::where('status', 'published')
                                 ->pluck('category')
                                 ->unique();

        $articles = $query->latest('published_at')->paginate(6)->withQueryString();

        return view('publications.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $publication = Publication::where('slug', $slug)
                                   ->where('status', 'published')
                                   ->firstOrFail();

        $recentPosts = Publication::where('status', 'published')
                                  ->where('id', '!=', $publication->id)
                                  ->latest('published_at')
                                  ->take(4)
                                  ->get();

        return view('publications.show', compact('publication', 'recentPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
