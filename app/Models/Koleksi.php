<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'photo',
        'category_id',
        'map_marker_id',
        'kerajaan',
        'divisi',
        'kelas',
        'order',
        'famili',
        'genus',
        'spesies',
        'otoritas_1',
        'otoritas_2',
    ];

    public function setAttribute($key, $value)
    {
        if (in_array($key, ['kerajaan', 'divisi', 'kelas', 'order', 'famili', 'genus']) && is_string($value)) {
            $value = ucwords(strtolower(trim($value)));
        } elseif ($key === 'spesies' && is_string($value)) {
            $value = strtolower(trim($value));
        }
        return parent::setAttribute($key, $value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vak()
    {
        return $this->belongsTo(MapMarker::class, 'map_marker_id');
    }
}
