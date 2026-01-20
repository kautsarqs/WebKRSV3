<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MapMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MapMarker::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $markers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.maps.index', compact('markers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'type' => ['required', 'in:area_koleksi,fasilitas_umum,kantor_pengelola,pos_keamanan'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('map_markers', 'public');
        }

        MapMarker::create($data);

        return redirect()->route('admin.maps.index')->with('success', 'Marker peta berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MapMarker $map)
    {
        return view('admin.maps.show', compact('map'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MapMarker $map)
    {
        return view('admin.maps.edit', compact('map'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MapMarker $map)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'type' => ['required', 'in:area_koleksi,fasilitas_umum,kantor_pengelola,pos_keamanan'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->except(['photo']);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($map->photo && Storage::disk('public')->exists($map->photo)) {
                Storage::disk('public')->delete($map->photo);
            }
            $data['photo'] = $request->file('photo')->store('map_markers', 'public');
        }

        $map->update($data);

        return redirect()->route('admin.maps.index')->with('success', 'Marker peta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MapMarker $map)
    {
        // Hapus foto jika ada
        if ($map->photo && Storage::disk('public')->exists($map->photo)) {
            Storage::disk('public')->delete($map->photo);
        }

        $map->delete();

        return redirect()->route('admin.maps.index')->with('success', 'Marker peta berhasil dihapus.');
    }
}
