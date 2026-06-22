<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\KoleksiLocation;
use App\Models\MapMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageOptimizer;

class KoleksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Koleksi::query()->with('locations');

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $koleksis = $query->latest()->paginate(10);
        return view('admin.koleksi.index', compact('koleksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.koleksi.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'kerajaan' => 'nullable|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'order' => 'nullable|string|max:255',
            'famili' => 'nullable|string|max:255',
            'genus' => 'nullable|string|max:255',
            'spesies' => 'nullable|string|max:255',
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
            ]);
            
            DB::commit();

            return redirect()->route('admin.koleksi.index')
                ->with('success', 'Koleksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan koleksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Koleksi $koleksi)
    {
        return view('koleksi.show', compact('koleksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Koleksi $koleksi)
    {
        return view('admin.koleksi.edit', compact('koleksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Koleksi $koleksi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'kerajaan' => 'nullable|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'order' => 'nullable|string|max:255',
            'famili' => 'nullable|string|max:255',
            'genus' => 'nullable|string|max:255',
            'spesies' => 'nullable|string|max:255',
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
            ]);

            DB::commit();

            return redirect()->route('admin.koleksi.index')
                ->with('success', 'Koleksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui koleksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Koleksi $koleksi)
    {
        if ($koleksi->photo) {
            Storage::disk('public')->delete($koleksi->photo);
        }
        $koleksi->delete();

        return redirect()->route('admin.koleksi.index')
            ->with('success', 'Koleksi berhasil dihapus.');
    }

    /**
     * Display a listing of the resource for public.
     */
    public function publicIndex(Request $request)
    {
        $query = Koleksi::query()->with(['locations']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('genus', 'like', '%' . $search . '%')
                  ->orWhere('spesies', 'like', '%' . $search . '%');
            });
        }

        $koleksis = $query->orderBy('title', 'asc')->get();

        return view('koleksi.index', compact('koleksis'));
    }
}
