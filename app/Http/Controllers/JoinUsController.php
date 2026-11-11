<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JoinUsController extends Controller
{
    /**
     * Storage path for join-us settings.
     */
    protected function getStoragePath(): string
    {
        return storage_path('app/join_us_settings.json');
    }

    /**
     * Get settings with defaults.
     */
    public function getSettings(): array
    {
        $path = $this->getStoragePath();
        $defaults = [
            'volunteer' => [
                'title' => 'Ikut Volunteer (Relawan Komunitas)',
                'form_link' => 'https://docs.google.com/forms/d/e/1FAIpQLSc-volunteer-sample/viewform',
                'status' => 'Buka',
                'description' => 'Bergabung sebagai pendamping membaca dan penggerak kegiatan komunitas.',
            ],
            'collaboration' => [
                'title' => 'Ajukan Kerja Sama Kolaborasi',
                'form_link' => 'https://docs.google.com/forms/d/e/1FAIpQLSc-kolaborasi-sample/viewform',
                'contact_email' => 'kerjasama@literasitamsel.org',
                'contact_whatsapp' => '+62 812-3456-7890',
                'status' => 'Buka',
                'description' => 'Buka peluang kemitraan program, publikasi, atau dukungan operasional.',
            ],
            'events' => [
                [
                    'id' => 1,
                    'title' => 'Membaca Bersama Gerobak Angkasa',
                    'category' => 'Volunteer Event',
                    'badge_top' => "GEROBAK\nANGKASA",
                    'badge_bottom' => 'RUANG BACA BERGERAK',
                    'area' => 'Tambun Selatan, Kabupaten Bekasi',
                    'schedule' => 'Akan diumumkan',
                    'deadline' => 'Belum ditentukan',
                    'quota' => '15 / 20 Relawan',
                    'status' => 'Buka',
                    'form_link' => 'https://docs.google.com/forms/d/e/1FAIpQLSc-event-sample/viewform',
                    'description' => 'Dampingi anak-anak memilih buku dan membaca bersama dalam kegiatan literasi komunitas di Tambun Selatan.',
                    'slug' => 'gerobak-angkasa',
                ],
            ],
        ];

        if (File::exists($path)) {
            $saved = json_decode(File::get($path), true);
            if (is_array($saved)) {
                return array_replace_recursive($defaults, $saved);
            }
        }

        return $defaults;
    }

    /**
     * Save settings to file.
     */
    protected function saveSettings(array $settings): void
    {
        $dir = dirname($this->getStoragePath());
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($this->getStoragePath(), json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function indexPublic()
    {
        $settings = $this->getSettings();

        return view('join-us', compact('settings'));
    }

    public function indexEvents()
    {
        $settings = $this->getSettings();
        $events = $settings['events'] ?? [];

        return view('join-us.events', compact('settings', 'events'));
    }

    public function showEventDetail($slug = null)
    {
        $settings = $this->getSettings();
        $events = $settings['events'] ?? [];
        $event = null;

        if ($slug) {
            foreach ($events as $item) {
                if (($item['slug'] ?? '') === $slug) {
                    $event = $item;
                    break;
                }
            }
        }

        if (! $event) {
            $event = $events[0] ?? [
                'id' => 1,
                'title' => 'Membaca Bersama Gerobak Angkasa',
                'badge_top' => "GEROBAK\nANGKASA",
                'badge_bottom' => 'RUANG BACA BERGERAK',
                'area' => 'Tambun Selatan, Kabupaten Bekasi',
                'schedule' => 'Akan diumumkan',
                'deadline' => 'Belum ditentukan',
                'quota' => '15 / 20 Relawan',
                'status' => 'Buka',
                'form_link' => '',
                'description' => 'Dampingi anak-anak memilih buku dan membaca bersama dalam kegiatan literasi komunitas di Tambun Selatan.',
                'slug' => 'gerobak-angkasa',
            ];
        }

        return view('join-us.event-detail', compact('settings', 'event'));
    }

    public function indexAdmin(Request $request)
    {
        $settings = $this->getSettings();
        $edit = $request->query('edit'); // 'volunteer', 'event', 'collaboration', or null

        return view('admin.join-us.index', compact('settings', 'edit'));
    }

    public function updateAdmin(Request $request)
    {
        $section = $request->input('section'); // 'volunteer' or 'collaboration'
        $settings = $this->getSettings();

        if ($section === 'volunteer') {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'form_link' => ['required', 'url', 'max:500'],
                'status' => ['required', 'string', 'in:Buka,Tutup'],
                'description' => ['nullable', 'string', 'max:1000'],
            ]);

            $settings['volunteer'] = array_merge($settings['volunteer'], $validated);
            $this->saveSettings($settings);

            return redirect()->route('admin.join-us.index', ['edit' => 'volunteer'])
                ->with('success', 'Pengaturan tautan formulir "Ikut Volunteer" berhasil diperbarui!');
        } elseif ($section === 'collaboration') {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'form_link' => ['required', 'url', 'max:500'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'contact_whatsapp' => ['nullable', 'string', 'max:50'],
                'status' => ['required', 'string', 'in:Buka,Tutup'],
                'description' => ['nullable', 'string', 'max:1000'],
            ]);

            $settings['collaboration'] = array_merge($settings['collaboration'], $validated);
            $this->saveSettings($settings);

            return redirect()->route('admin.join-us.index', ['edit' => 'collaboration'])
                ->with('success', 'Pengaturan tautan formulir "Ajukan Kerja Sama Kolaborasi" berhasil diperbarui!');
        }

        return redirect()->route('admin.join-us.index');
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'string', 'max:255'],
            'quota' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Buka,Tutup'],
            'form_link' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'poster_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'juknis_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $settings = $this->getSettings();
        $events = $settings['events'] ?? [];
        $nextId = count($events) > 0 ? max(array_column($events, 'id')) + 1 : 1;

        $slug = Str::slug($validated['title']);
        $validated['id'] = $nextId;
        $validated['slug'] = $slug ?: 'event-'.$nextId;
        $validated['category'] = 'Volunteer Event';
        $words = explode(' ', trim($validated['title']));
        $validated['badge_top'] = strtoupper(implode(' ', array_slice($words, 0, 2)));
        $validated['badge_bottom'] = 'RUANG BACA BERGERAK';

        if ($request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            $fileName = 'event_poster_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
            $destination = public_path('assets/images/events');
            $file->move($destination, $fileName);
            $validated['poster_image'] = $fileName;
        }

        if ($request->hasFile('juknis_file')) {
            $file = $request->file('juknis_file');
            $fileName = 'juknis_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
            $destination = public_path('assets/documents/juknis');
            $file->move($destination, $fileName);
            $validated['juknis_file'] = $fileName;
        }

        $events[] = $validated;
        $settings['events'] = $events;
        $this->saveSettings($settings);

        return redirect()->route('admin.join-us.index', ['edit' => 'event'])
            ->with('success', 'Event sukarelawan "'.$validated['title'].'" berhasil ditambahkan!');
    }

    public function updateEvent(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'string', 'max:255'],
            'quota' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Buka,Tutup'],
            'form_link' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'poster_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'juknis_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $settings = $this->getSettings();
        $events = $settings['events'] ?? [];
        $found = false;

        foreach ($events as &$evt) {
            if ($evt['id'] == $id) {
                if ($request->hasFile('poster_image')) {
                    $file = $request->file('poster_image');
                    $fileName = 'event_poster_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
                    $destination = public_path('assets/images/events');
                    $file->move($destination, $fileName);
                    $validated['poster_image'] = $fileName;
                }

                if ($request->hasFile('juknis_file')) {
                    $file = $request->file('juknis_file');
                    $fileName = 'juknis_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
                    $destination = public_path('assets/documents/juknis');
                    $file->move($destination, $fileName);
                    $validated['juknis_file'] = $fileName;
                }

                $evt = array_merge($evt, $validated);
                $found = true;
                break;
            }
        }

        if ($found) {
            $settings['events'] = $events;
            $this->saveSettings($settings);
        }

        return redirect()->route('admin.join-us.index', ['edit' => 'event'])
            ->with('success', 'Event "'.$validated['title'].'" berhasil diperbarui!');
    }

    public function destroyEvent($id)
    {
        $settings = $this->getSettings();
        $events = $settings['events'] ?? [];
        $settings['events'] = array_values(array_filter($events, function ($e) use ($id) {
            return $e['id'] != $id;
        }));

        $this->saveSettings($settings);

        return redirect()->route('admin.join-us.index', ['edit' => 'event'])
            ->with('success', 'Event sukarelawan berhasil dihapus!');
    }
}
