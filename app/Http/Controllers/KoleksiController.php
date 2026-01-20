<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\KoleksiLocation;
use App\Models\MapMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'category_id' => 'required|integer|exists:categories,id',
            'locations' => 'nullable|array',
            'locations.*.name' => 'required|string|max:255',
            'locations.*.latitude' => 'required|numeric|between:-90,90',
            'locations.*.longitude' => 'required|numeric|between:-180,180',
        ]);

        DB::beginTransaction();
        try {
            $path = null;
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('koleksi', 'public');
            }

            $koleksi = Koleksi::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'photo' => $path,
                'category_id' => $validated['category_id'],
            ]);

            if (isset($validated['locations'])) {
                foreach ($validated['locations'] as $locationData) {
                    $koleksi->locations()->create($locationData);
                }
            }
            
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
        $koleksi->load('locations');
        return view('koleksi.show', compact('koleksi'));
    }

    /**
     * Display the map for a specified resource.
     */
    public function showMap(Koleksi $koleksi)
    {
        $koleksi->load('locations');
        $markers = MapMarker::all();
        return view('koleksi.peta', compact('koleksi', 'markers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Koleksi $koleksi)
    {
        $koleksi->load('locations');
        $categories = \App\Models\Category::all();
        return view('admin.koleksi.edit', compact('koleksi', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Koleksi $koleksi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'locations' => 'nullable|array',
            'locations.*.name' => 'required|string|max:255',
            'locations.*.latitude' => 'required|numeric|between:-90,90',
            'locations.*.longitude' => 'required|numeric|between:-180,180',
        ]);
        
        DB::beginTransaction();
        try {
            $path = $koleksi->photo;
            if ($request->hasFile('photo')) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
                $path = $request->file('photo')->store('koleksi', 'public');
            }

            $koleksi->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'photo' => $path,
                'category_id' => $validated['category_id'],
            ]);

            // Delete old locations and create new ones
            $koleksi->locations()->delete();
            if (isset($validated['locations'])) {
                foreach ($validated['locations'] as $locationData) {
                    $koleksi->locations()->create($locationData);
                }
            }

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
    public function publicIndex()
    {
        $categories = \App\Models\Category::with(['koleksis' => function ($query) {
            $query->orderBy('title', 'asc');
        }, 'koleksis.locations'])->orderBy('name', 'asc')->get();
        return view('koleksi.index', compact('categories'));
    }
}
