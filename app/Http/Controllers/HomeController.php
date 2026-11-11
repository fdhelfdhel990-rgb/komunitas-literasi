<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publication;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        $latestPublications = collect();
        $latestBooks = collect();

        try {
            if (Schema::hasTable('publications')) {
                $latestPublications = Publication::where('status', 'published')
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            }
        } catch (Throwable) {
            $latestPublications = collect();
        }

        try {
            if (Schema::hasTable('books')) {
                $latestBooks = Book::with(['author', 'genre'])
                    ->where('is_published', true)
                    ->latest()
                    ->take(4)
                    ->get();
            }
        } catch (Throwable) {
            $latestBooks = collect();
        }

        $mediaPartners = MediaPartnerController::getPartners();
        $settings = SettingController::getSettings();

        return view('home', compact('latestPublications', 'latestBooks', 'mediaPartners', 'settings'));
    }
}
