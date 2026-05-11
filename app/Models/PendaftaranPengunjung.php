<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPengunjung extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_identitas',
        'nomor_hp',
        'tanggal_kunjungan',
        'jumlah_rombongan',
        'keperluan',
        'status',
    ];

    // Relasi ke User (Opsional, untuk dashboard nanti)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}