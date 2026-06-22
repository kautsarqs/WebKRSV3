<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'type',
        'description',
        'color',
        'photo',
        'geometry_type',
        'geojson',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function koleksis()
    {
        return $this->belongsToMany(Koleksi::class, 'koleksi_map_marker');
    }
}
