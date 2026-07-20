<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageOptimizer;

class KoleksiController extends Controller
{

    public function index(Request $request)
    {
        $query = Koleksi::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        $koleksis = $query->latest()->paginate(10);
        return view('admin.koleksi.index', compact('koleksis'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.koleksi.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'kerajaan' => 'nullable|string|max:50',
            'divisi' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'order' => 'nullable|string|max:50',
            'famili' => 'nullable|string|max:100',
            'genus' => 'nullable|string|max:100',
            'spesies' => 'nullable|string|max:100',
            'otoritas_1' => 'nullable|string|max:100',
            'otoritas_2' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $path = null;
            if ($request->hasFile('photo')) {
                $path = ImageOptimizer::convertToAvif($request->file('photo'), 'koleksi');
            }

            $koleksi = Koleksi::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'photo' => $path,
                'kerajaan' => $validated['kerajaan'] ?? null,
                'divisi' => $validated['divisi'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
                'order' => $validated['order'] ?? null,
                'famili' => $validated['famili'] ?? null,
                'genus' => $validated['genus'] ?? null,
                'spesies' => $validated['spesies'] ?? null,
                'otoritas_1' => $validated['otoritas_1'] ?? null,
                'otoritas_2' => $validated['otoritas_2'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.koleksi.index')
                ->with('success', 'Koleksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan koleksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Koleksi $koleksi)
    {
        return view('koleksi.show', compact('koleksi'));
    }

    public function edit(Koleksi $koleksi)
    {
        return view('admin.koleksi.edit', compact('koleksi'));
    }

    public function update(Request $request, Koleksi $koleksi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'kerajaan' => 'nullable|string|max:50',
            'divisi' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'order' => 'nullable|string|max:50',
            'famili' => 'nullable|string|max:100',
            'genus' => 'nullable|string|max:100',
            'spesies' => 'nullable|string|max:100',
            'otoritas_1' => 'nullable|string|max:100',
            'otoritas_2' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $path = $koleksi->photo;
            if ($request->hasFile('photo')) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
                $path = ImageOptimizer::convertToAvif($request->file('photo'), 'koleksi');
            }

            $koleksi->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'photo' => $path,
                'kerajaan' => $validated['kerajaan'] ?? null,
                'divisi' => $validated['divisi'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
                'order' => $validated['order'] ?? null,
                'famili' => $validated['famili'] ?? null,
                'genus' => $validated['genus'] ?? null,
                'spesies' => $validated['spesies'] ?? null,
                'otoritas_1' => $validated['otoritas_1'] ?? null,
                'otoritas_2' => $validated['otoritas_2'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.koleksi.index')
                ->with('success', 'Koleksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui koleksi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Koleksi $koleksi)
    {
        if ($koleksi->photo) {
            Storage::disk('public')->delete($koleksi->photo);
        }
        $koleksi->delete();

        return redirect()->route('admin.koleksi.index')
            ->with('success', 'Koleksi berhasil dihapus.');
    }

    public function publicIndex(Request $request)
    {
        $query = Koleksi::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%')
                  ->orWhere('genus', 'ilike', '%' . $search . '%')
                  ->orWhere('spesies', 'ilike', '%' . $search . '%');
            });
        }

        $koleksis = $query->orderBy('title', 'asc')->get();

        return view('koleksi.index', compact('koleksis'));
    }
}
