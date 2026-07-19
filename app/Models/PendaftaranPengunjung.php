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
        'instansi',
        'rombongan_details',
        'catatan_admin',
        'parent_id',
    ];

    protected $casts = [
        'rombongan_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function editedVersion()
    {
        return $this->hasOne(self::class, 'parent_id');
    }
}