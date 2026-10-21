<?php

namespace App\Http\Controllers;

use App\Models\Troubleshoot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TroubleshootController extends Controller
{
    // Menampilkan daftar masalah dengan fitur pencarian
    public function index(Request $request)
    {
        $search = $request->input('search');

        $troubleshoots = Troubleshoot::when($search, function ($query, $search) {
            return $query->where('judul_masalah', 'like', "%{$search}%")
                         ->orWhere('kategori', 'like', "%{$search}%")
                         ->orWhere('gejala', 'like', "%{$search}%")
                         ->orWhere('solusi', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('troubleshoot.index', compact('troubleshoots', 'search'));
    }

    // Form tambah masalah
    public function create()
    {
        return view('troubleshoot.create');
    }

    // Menyimpan data masalah baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'catatan' => 'nullable|string',
            'judul_masalah' => 'nullable|string|max:255',
            'gejala' => 'nullable|string',
            'solusi' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
            'fotos' => 'nullable|array|max:8',
            'fotos.*' => 'image|max:4096',
            'video' => 'nullable|file|mimes:mp4,webm,mov|max:15360',
        ]);

        $validated['judul_masalah'] = $validated['kategori'];

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('troubleshoots', 'public');
        }

        if ($request->hasFile('fotos')) {
            $validated['fotos'] = array_map(
                fn ($photo) => $photo->store('troubleshoots', 'public'),
                $request->file('fotos')
            );
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('troubleshoots/videos', 'public');
        }

        Troubleshoot::create($validated);

        return redirect()->route('troubleshoot.index')->with('success', 'Data masalah dan solusi berhasil disimpan!');
    }

    // Menampilkan detail masalah
    public function show(Troubleshoot $troubleshoot)
    {
        return view('troubleshoot.show', compact('troubleshoot'));
    }

    // Form edit masalah
    public function edit(Troubleshoot $troubleshoot)
    {
        return view('troubleshoot.edit', compact('troubleshoot'));
    }

    // Update data masalah
    public function update(Request $request, Troubleshoot $troubleshoot)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'catatan' => 'nullable|string',
            'judul_masalah' => 'nullable|string|max:255',
            'gejala' => 'nullable|string',
            'solusi' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
            'fotos' => 'nullable|array|max:8',
            'fotos.*' => 'image|max:4096',
            'video' => 'nullable|file|mimes:mp4,webm,mov|max:15360',
        ]);

        if (! $troubleshoot->judul_masalah) {
            $validated['judul_masalah'] = $validated['kategori'];
        }

        $fotoLama = $troubleshoot->foto;
        $videoLama = $troubleshoot->video;

        $fotoBaru = $request->file('fotos', []);
        $jumlahFotoLama = count(array_filter(array_merge(
            [$troubleshoot->foto],
            $troubleshoot->fotos ?? []
        )));

        if ($jumlahFotoLama + count($fotoBaru) > 8) {
            return back()->withErrors([
            'fotos' => 'Maksimal 8 foto per catatan, termasuk foto yang sudah tersimpan.',
            ])->withInput();
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('troubleshoots', 'public');
        }

        if ($fotoBaru) {
            $fotosBaru = array_map(
                fn ($photo) => $photo->store('troubleshoots', 'public'),
            $fotoBaru
            );
            $validated['fotos'] = array_merge($troubleshoot->fotos ?? [], $fotosBaru);
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('troubleshoots/videos', 'public');
        }

        $troubleshoot->update($validated);

        if (isset($validated['foto']) && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        if (isset($validated['video']) && $videoLama) {
            Storage::disk('public')->delete($videoLama);
        }

        return redirect()->route('troubleshoot.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Hapus data
    public function destroy(Troubleshoot $troubleshoot)
    {
        $media = array_filter(array_merge(
            [$troubleshoot->foto, $troubleshoot->video],
            $troubleshoot->fotos ?? []
        ));
        $troubleshoot->delete();

        if ($media) {
            Storage::disk('public')->delete($media);
        }

        return redirect()->route('troubleshoot.index')->with('success', 'Data berhasil dihapus!');
    }
}