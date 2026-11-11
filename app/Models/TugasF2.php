<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasF2 extends Model
{
    use HasFactory;

    protected $table = 'form_f2_tugas';

    protected $fillable = [
        'pengajuan_magang_id',
        'modul_pekerjaan',
        'spesifikasi_kebutuhan',
        'target_minggu',
        'status',
        'catatan_industri',
    ];

    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_magang_id');
    }
}
