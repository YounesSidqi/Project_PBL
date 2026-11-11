<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganF4 extends Model
{
    use HasFactory;

    protected $table = 'form_f4_bimbingan';

    protected $fillable = [
        'pengajuan_magang_id',
        'mahasiswa_id',
        'dosen_id',
        'sesi_ke',
        'tanggal_bimbingan',
        'materi_bimbingan',
        'progres_laporan',
        'catatan_dosen',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bimbingan' => 'date',
            'sesi_ke' => 'integer',
        ];
    }

    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_magang_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
