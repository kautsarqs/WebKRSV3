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

    public function getCenterLatitudeAttribute()
    {
        if ($this->geometry_type === 'point') {
            return $this->latitude;
        }

        if ($this->geojson) {
            $coords = json_decode($this->geojson, true);
            if (is_array($coords) && count($coords) > 0) {
                $lats = array_column($coords, 0);
                return count($lats) > 0 ? array_sum($lats) / count($lats) : null;
            }
        }

        return $this->latitude;
    }

    public function getCenterLongitudeAttribute()
    {
        if ($this->geometry_type === 'point') {
            return $this->longitude;
        }

        if ($this->geojson) {
            $coords = json_decode($this->geojson, true);
            if (is_array($coords) && count($coords) > 0) {
                $lngs = array_column($coords, 1);
                return count($lngs) > 0 ? array_sum($lngs) / count($lngs) : null;
            }
        }

        return $this->longitude;
    }
}
