<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranMagang extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_magangs';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_identitas',
        'nomor_hp',
        'institusi',
        'program_studi',
        'jenjang',
        'judul_magang',
        'bidang_magang',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_anggota',
        'tujuan_magang',
        'surat_pengantar',
        'status',
        'catatan_admin',
        'status_magang',
        'parent_id',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
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
