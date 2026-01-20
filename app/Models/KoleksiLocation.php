<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoleksiLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'koleksi_id',
        'name',
        'latitude',
        'longitude',
    ];

    public function koleksi()
    {
        return $this->belongsTo(Koleksi::class);
    }
}
