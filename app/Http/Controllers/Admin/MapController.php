<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MapMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageOptimizer;

class MapController extends Controller
{

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

    public function create()
    {
        $existingTypes = MapMarker::select('type')->distinct()->pluck('type');
        $existingMarkers = MapMarker::all();
        return view('admin.maps.create', compact('existingTypes', 'existingMarkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'geometry_type' => ['required', 'in:point,polyline,polygon,linestring'],
            'latitude' => ['required_if:geometry_type,point', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['required_if:geometry_type,point', 'nullable', 'numeric', 'between:-180,180'],
            'geojson' => ['required_if:geometry_type,polyline,polygon', 'nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,avif', 'max:2048'],
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = ImageOptimizer::convertToAvif($request->file('photo'), 'map_markers');
        }

        MapMarker::create($data);

        return redirect()->route('admin.maps.index')->with('success', 'Marker peta berhasil ditambahkan.');
    }

    public function show(MapMarker $map)
    {
        return view('admin.maps.show', compact('map'));
    }

    public function edit(MapMarker $map)
    {
        $existingTypes = MapMarker::select('type')->distinct()->pluck('type');
        $existingMarkers = MapMarker::where('id', '!=', $map->id)->get();
        return view('admin.maps.edit', compact('map', 'existingTypes', 'existingMarkers'));
    }

    public function update(Request $request, MapMarker $map)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'geometry_type' => ['required', 'in:point,polyline,polygon,linestring'],
            'latitude' => ['required_if:geometry_type,point', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['required_if:geometry_type,point', 'nullable', 'numeric', 'between:-180,180'],
            'geojson' => ['required_if:geometry_type,polyline,polygon', 'nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,avif', 'max:2048'],
        ]);

        $data = $request->except(['photo']);

        if ($request->hasFile('photo')) {

            if ($map->photo && Storage::disk('public')->exists($map->photo)) {
                Storage::disk('public')->delete($map->photo);
            }
            $data['photo'] = ImageOptimizer::convertToAvif($request->file('photo'), 'map_markers');
        }

        if ($request->geometry_type !== 'point') {
            $data['latitude'] = null;
            $data['longitude'] = null;
        } else {
            $data['geojson'] = null;
        }

        $map->update($data);

        return redirect()->route('admin.maps.index')->with('success', 'Marker peta berhasil diperbarui.');
    }

    public function destroy(MapMarker $map)
    {

        if ($map->photo && Storage::disk('public')->exists($map->photo)) {
            Storage::disk('public')->delete($map->photo);
        }

        $map->delete();

        return redirect()->route('admin.maps.index')->with('success', 'Marker peta berhasil dihapus.');
    }

    public function publicShow(MapMarker $map)
    {
        return view('landing.peta_show', compact('map'));
    }

    public function syncOffline(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'geometry_type' => ['required', 'in:point,polyline,polygon,linestring'],
            'latitude' => ['required_if:geometry_type,point', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['required_if:geometry_type,point', 'nullable', 'numeric', 'between:-180,180'],
            'geojson' => ['required_if:geometry_type,polyline,polygon', 'nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,avif', 'max:2048'],
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = ImageOptimizer::convertToAvif($request->file('photo'), 'map_markers');
        }

        MapMarker::create($data);

        return response()->json(['success' => true]);
    }
}
