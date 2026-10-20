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
            'judul_masalah' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'gejala' => 'required',
            'solusi' => 'required',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('troubleshoots', 'public');
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
            'judul_masalah' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'gejala' => 'required',
            'solusi' => 'required',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
        ]);

        $fotoLama = $troubleshoot->foto;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('troubleshoots', 'public');
        }

        $troubleshoot->update($validated);

        if (isset($validated['foto']) && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()->route('troubleshoot.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Hapus data
    public function destroy(Troubleshoot $troubleshoot)
    {
        $foto = $troubleshoot->foto;
        $troubleshoot->delete();

        if ($foto) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()->route('troubleshoot.index')->with('success', 'Data berhasil dihapus!');
    }
}