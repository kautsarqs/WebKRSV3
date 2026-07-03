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
        'kerajaan',
        'divisi',
        'kelas',
        'order',
        'famili',
        'genus',
        'spesies',
    ];

    /**
     * Enforce Title Case (capitalize first letter of each word) for taxonomy fields
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, ['kerajaan', 'divisi', 'kelas', 'order', 'famili', 'genus', 'spesies']) && is_string($value)) {
            $value = ucwords(strtolower(trim($value)));
        }
        return parent::setAttribute($key, $value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
