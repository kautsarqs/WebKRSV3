<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPeneliti extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_identitas',
        'nomor_hp',
        'institusi',
        'program_studi',
        'jenjang',
        'judul_penelitian',
        'bidang_penelitian',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_anggota',
        'tujuan_penelitian',
        'surat_pengantar',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
