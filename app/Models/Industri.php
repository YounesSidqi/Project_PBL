<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    protected $table = 'industris';

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'bidang_usaha',
        'alamat',
        'kota',
        'kontak_person',
        'email_pj',
        'telepon',
        'status_verifikasi',
        'catatan_admin',
    ];

    /**
     * Akun penanggung jawab industri (DUDI).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Pengajuan magang mahasiswa di industri ini.
     */
    public function pengajuanMagangs()
    {
        return $this->hasMany(PengajuanMagang::class, 'industri_id');
    }
}
