<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource for public view.
     */
    public function index(Request $request)
    {
        $query = Publication::where('status', 'published');

        if ($request->filled('q')) {
            $keyword = $request->query('q');
            $query->where(function ($q) use ($keyword) {
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
            ->unique()
            ->filter()
            ->values();

        $articles = $query->latest('published_at')->paginate(6)->withQueryString();

        return view('publications.index', compact('articles', 'categories'));
    }

    /**
     * Display the specified resource for public view.
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
     * Display a listing of publications for admin panel.
     */
    public function indexAdmin(Request $request)
    {
        $query = Publication::with('user');

        if ($request->filled('q')) {
            $keyword = trim($request->query('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('excerpt', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%")
                    ->orWhere('category', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('status')) {
            $statusVal = $request->query('status');
            if ($statusVal === 'Tayang') {
                $query->where('status', 'published');
            } elseif ($statusVal === 'Draf') {
                $query->where('status', 'draft');
            } else {
                $query->where('status', $statusVal);
            }
        }

        $articles = $query->latest('published_at')->paginate(6)->withQueryString();

        $categories = Publication::pluck('category')->filter()->unique()->values()->toArray();
        if (empty($categories)) {
            $categories = ['Kajian', 'Pendidikan', 'Sosial', 'Berita Komunitas', 'Opini & Literasi'];
        }

        return view('admin.publications.index', compact('articles', 'categories'));
    }

    /**
     * Show the dedicated create page for publication in admin.
     */
    public function createAdmin()
    {
        $categories = Publication::pluck('category')->filter()->unique()->values()->toArray();
        if (empty($categories)) {
            $categories = ['Kajian', 'Pendidikan', 'Sosial', 'Berita Komunitas', 'Opini & Literasi'];
        }

        return view('admin.publications.create', compact('categories'));
    }

    /**
     * Show the dedicated edit page for publication in admin.
     */
    public function editAdmin($id)
    {
        $article = Publication::with('user')->findOrFail($id);
        $categories = Publication::pluck('category')->filter()->unique()->values()->toArray();
        if (empty($categories)) {
            $categories = ['Kajian', 'Pendidikan', 'Sosial', 'Berita Komunitas', 'Opini & Literasi'];
        }

        return view('admin.publications.edit', compact('article', 'categories'));
    }

    /**
     * Store a newly created publication in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
            'content' => ['required', 'string'],
            'status' => ['nullable', 'string', 'in:published,draft,Tayang,Draf'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
        ]);

        $status = in_array($validated['status'] ?? 'published', ['published', 'Tayang']) ? 'published' : 'draft';

        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase ?: 'publikasi-'.time();
        if (Publication::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(5);
        }

        $imageName = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $imageName = time().'-'.Str::random(6).'-'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('assets/images/publications'), $imageName);
        }

        $excerpt = Str::limit(strip_tags($validated['content']), 150);

        $userId = auth()->id() ?? (User::first()->id ?? 1);

        Publication::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'published_at' => $validated['published_at'] ?? now()->toDateString(),
            'excerpt' => $excerpt,
            'content' => $validated['content'],
            'featured_image_path' => $imageName,
            'status' => $status,
            'created_by' => $userId,
        ]);

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publikasi "'.$validated['title'].'" berhasil diterbitkan!');
    }

    /**
     * Update the specified publication in storage.
     */
    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
            'content' => ['required', 'string'],
            'status' => ['nullable', 'string', 'in:published,draft,Tayang,Draf'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $status = in_array($validated['status'] ?? 'published', ['published', 'Tayang']) ? 'published' : 'draft';

        if ($publication->title !== $validated['title']) {
            $slugBase = Str::slug($validated['title']);
            $slug = $slugBase ?: 'publikasi-'.time();
            if (Publication::where('slug', $slug)->where('id', '!=', $publication->id)->exists()) {
                $slug .= '-'.Str::random(5);
            }
            $publication->slug = $slug;
        }

        if ($request->boolean('remove_image')) {
            if ($publication->featured_image_path && file_exists(public_path('assets/images/publications/'.$publication->featured_image_path))) {
                @unlink(public_path('assets/images/publications/'.$publication->featured_image_path));
            }
            $publication->featured_image_path = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($publication->featured_image_path && file_exists(public_path('assets/images/publications/' . $publication->featured_image_path))) {
                @unlink(public_path('assets/images/publications/' . $publication->featured_image_path));
            }

            $file = $request->file('featured_image');
            $imageName = time() . '-' . Str::random(6) . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/publications'), $imageName);
            $publication->featured_image_path = $imageName;
        }

        $publication->title = $validated['title'];
        $publication->category = $validated['category'];
        $publication->published_at = $validated['published_at'] ?? $publication->published_at ?? now()->toDateString();
        $publication->content = $validated['content'];
        $publication->excerpt = Str::limit(strip_tags($validated['content']), 150);
        $publication->status = $status;
        $publication->save();

        return redirect()->route('admin.publications.index')
            ->with('success', 'Perubahan publikasi "'.$publication->title.'" berhasil disimpan!');
    }

    /**
     * Remove the specified publication from storage.
     */
    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        $title = $publication->title;

        if ($publication->featured_image_path && file_exists(public_path('assets/images/publications/'.$publication->featured_image_path))) {
            @unlink(public_path('assets/images/publications/'.$publication->featured_image_path));
        }

        $publication->delete();

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publikasi "'.$title.'" berhasil dihapus!');
    }
}
