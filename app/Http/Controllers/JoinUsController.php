<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JoinUsController extends Controller
{
    protected function getStoragePath(): string
    {
        return storage_path('app/join_us_settings.json');
    }

    public function getSettings(): array
    {
        $path = $this->getStoragePath();
        $defaults = [
            'volunteer' => [
                'title'       => 'Ikut Volunteer (Relawan Komunitas)',
                'form_link'   => 'https://docs.google.com/forms/d/e/1FAIpQLSc-volunteer-sample/viewform',
                'status'      => 'Buka',
                'description' => 'Bergabung sebagai pendamping membaca dan penggerak kegiatan komunitas.',
            ],
            'collaboration' => [
                'title'            => 'Ajukan Kerja Sama Kolaborasi',
                'form_link'        => 'https://docs.google.com/forms/d/e/1FAIpQLSc-kolaborasi-sample/viewform',
                'contact_email'    => 'kerjasama@literasitamsel.org',
                'contact_whatsapp' => '+62 812-3456-7890',
                'status'           => 'Buka',
                'description'      => 'Buka peluang kemitraan program, publikasi, atau dukungan operasional.',
            ],
        ];

        $settings = $defaults;
        if (File::exists($path)) {
            $saved = json_decode(File::get($path), true);
            if (is_array($saved)) {
                unset($saved['events']);
                $settings = array_replace_recursive($defaults, $saved);
            }
        }

        // Ambil daftar event langsung dari tabel database `events`
        $settings['events'] = Event::latest()->get()->map(function (Event $event) {
            return [
                'id'           => $event->id,
                'title'        => $event->name,
                'category'     => 'Volunteer Event',
                'badge_top'    => $event->badge_top,
                'badge_bottom' => $event->badge_bottom,
                'area'         => $event->location,
                'schedule'     => $event->schedule ? $event->schedule->translatedFormat('d F Y') : 'Akan diumumkan',
                'deadline'     => $event->registration_deadline ? $event->registration_deadline->translatedFormat('d F Y') : 'Belum ditentukan',
                'quota'        => $event->volunteer_quota !== null ? $event->volunteer_quota . ' Relawan' : '-',
                'status'       => $event->registration_status,
                'form_link'    => $event->form_url,
                'description'  => $event->description,
                'poster_image' => $event->poster_path,
                'juknis_file'  => $event->document_path,
                'slug'         => $event->slug,
            ];
        })->toArray();

        return $settings;
    }

    protected function saveSettings(array $settings): void
    {
        unset($settings['events']); // Pastikan events tidak ikut tersimpan ke JSON

        $dir = dirname($this->getStoragePath());
        File::ensureDirectoryExists($dir);

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
        $events = $settings['events'];

        return view('join-us.events', compact('settings', 'events'));
    }

    public function showEventDetail($slug = null)
    {
        $settings = $this->getSettings();
        $events = $settings['events'];
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
            $event = $events[0] ?? abort(404, 'Event tidak ditemukan.');
        }

        return view('join-us.event-detail', compact('settings', 'event'));
    }

    protected function checkPrivilege(): void
    {
        if (! Auth::check() || ! Auth::user()->canManage('join_us')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengelola Join Us.');
        }
    }

    public function indexAdmin(Request $request)
    {
        $settings = $this->getSettings();
        $edit = $request->query('edit');

        return view('admin.join-us.index', compact('settings', 'edit'));
    }

    public function createEvent()
    {
        $this->checkPrivilege();

        return view('admin.join-us.events.create');
    }

    public function editEvent($id)
    {
        $this->checkPrivilege();

        $event = Event::findOrFail($id);

        return view('admin.join-us.events.edit', compact('event'));
    }

    public function updateAdmin(Request $request)
    {
        $this->checkPrivilege();
        $section = $request->input('section');
        $settings = $this->getSettings();

        if ($section === 'volunteer') {
            $validated = $request->validate([
                'title'       => ['required', 'string', 'max:255'],
                'form_link'   => ['required', 'url', 'max:500'],
                'status'      => ['required', 'string', 'in:Buka,Tutup'],
                'description' => ['nullable', 'string', 'max:1000'],
            ]);

            $settings['volunteer'] = array_merge($settings['volunteer'], $validated);
            $this->saveSettings($settings);

            return redirect()->route('admin.join-us.index', ['edit' => 'volunteer'])
                ->with('success', 'Pengaturan tautan formulir "Ikut Volunteer" berhasil diperbarui!');
        } elseif ($section === 'collaboration') {
            $validated = $request->validate([
                'title'            => ['required', 'string', 'max:255'],
                'form_link'        => ['required', 'url', 'max:500'],
                'contact_email'    => ['nullable', 'email', 'max:255'],
                'contact_whatsapp' => ['nullable', 'string', 'max:50'],
                'status'           => ['required', 'string', 'in:Buka,Tutup'],
                'description'      => ['nullable', 'string', 'max:1000'],
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
        $this->checkPrivilege();

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'area'         => ['required', 'string', 'max:255'],
            'schedule'     => ['nullable', 'date'],
            'deadline'     => ['nullable', 'date'],
            'quota'        => ['nullable', 'integer', 'min:1'],
            'status'       => ['required', 'string', 'in:Buka,Tutup'],
            'form_link'    => ['nullable', 'url', 'max:500'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'poster_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'juknis_file'  => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $posterPath = null;
        if ($request->hasFile('poster_image')) {
            $dir = public_path('assets/images/events');
            File::ensureDirectoryExists($dir);
            $file = $request->file('poster_image');
            $posterPath = 'event_poster_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $posterPath);
        }

        $documentPath = null;
        if ($request->hasFile('juknis_file')) {
            $dir = public_path('assets/documents/juknis');
            File::ensureDirectoryExists($dir);
            $file = $request->file('juknis_file');
            $documentPath = 'juknis_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $documentPath);
        }

        Event::create([
            'name'                  => $validated['title'],
            'slug'                  => $this->generateUniqueSlug($validated['title']),
            'location'              => $validated['area'],
            'registration_status'   => $validated['status'],
            'schedule'              => $validated['schedule'] ?? null,
            'registration_deadline' => $validated['deadline'] ?? null,
            'volunteer_quota'       => $validated['quota'] ?? null,
            'form_url'              => $validated['form_link'] ?? null,
            'poster_path'           => $posterPath,
            'document_path'         => $documentPath,
            'description'           => $validated['description'] ?? null,
            'created_by'            => Auth::id(),
        ]);

        return redirect()->route('admin.join-us.index', ['edit' => 'event'])
            ->with('success', 'Event sukarelawan "' . $validated['title'] . '" berhasil ditambahkan!');
    }

    public function updateEvent(Request $request, $id)
    {
        $this->checkPrivilege();

        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'area'          => ['required', 'string', 'max:255'],
            'schedule'      => ['nullable', 'date'],
            'deadline'      => ['nullable', 'date'],
            'quota'         => ['nullable', 'integer', 'min:1'],
            'status'        => ['required', 'string', 'in:Buka,Tutup'],
            'form_link'     => ['nullable', 'url', 'max:500'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'poster_image'  => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'juknis_file'   => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'remove_poster' => ['nullable', 'boolean'],
        ]);

        $posterDir = public_path('assets/images/events');
        $posterPath = $event->poster_path;

        if ($request->boolean('remove_poster') && ! $request->hasFile('poster_image')) {
            if ($posterPath && File::exists($posterDir . '/' . $posterPath)) {
                File::delete($posterDir . '/' . $posterPath);
            }
            $posterPath = null;
        }

        if ($request->hasFile('poster_image')) {
            File::ensureDirectoryExists($posterDir);
            if ($posterPath && File::exists($posterDir . '/' . $posterPath)) {
                File::delete($posterDir . '/' . $posterPath);
            }
            $file = $request->file('poster_image');
            $posterPath = 'event_poster_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move($posterDir, $posterPath);
        }

        $docDir = public_path('assets/documents/juknis');
        $documentPath = $event->document_path;

        if ($request->hasFile('juknis_file')) {
            File::ensureDirectoryExists($docDir);
            if ($documentPath && File::exists($docDir . '/' . $documentPath)) {
                File::delete($docDir . '/' . $documentPath);
            }
            $file = $request->file('juknis_file');
            $documentPath = 'juknis_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move($docDir, $documentPath);
        }

        $slug = $event->name !== $validated['title']
            ? $this->generateUniqueSlug($validated['title'], $event->id)
            : $event->slug;

        $event->update([
            'name'                  => $validated['title'],
            'slug'                  => $slug,
            'location'              => $validated['area'],
            'registration_status'   => $validated['status'],
            'schedule'              => $validated['schedule'] ?? null,
            'registration_deadline' => $validated['deadline'] ?? null,
            'volunteer_quota'       => $validated['quota'] ?? null,
            'form_url'              => $validated['form_link'] ?? null,
            'poster_path'           => $posterPath,
            'document_path'         => $documentPath,
            'description'           => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.join-us.index', ['edit' => 'event'])
            ->with('success', 'Event "' . $validated['title'] . '" berhasil diperbarui!');
    }

    public function destroyEvent($id)
    {
        $this->checkPrivilege();

        $event = Event::findOrFail($id);

        if ($event->poster_path && File::exists(public_path('assets/images/events/' . $event->poster_path))) {
            File::delete(public_path('assets/images/events/' . $event->poster_path));
        }

        if ($event->document_path && File::exists(public_path('assets/documents/juknis/' . $event->document_path))) {
            File::delete(public_path('assets/documents/juknis/' . $event->document_path));
        }

        $event->delete();

        return redirect()->route('admin.join-us.index', ['edit' => 'event'])
            ->with('success', 'Event sukarelawan berhasil dihapus!');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug ?: 'event';
        $counter = 2;

        while (
            Event::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = ($baseSlug ?: 'event') . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
