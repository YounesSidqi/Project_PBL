<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiF6 extends Model
{
    use HasFactory;

    protected $table = 'form_f6_evaluasi';

    protected $fillable = [
        'pengajuan_magang_id',
        'skor_kesesuaian_judul',
        'skor_tata_bahasa',
        'skor_format_laporan',
        'skor_pengetahuan',
        'skor_tanya_jawab',
        'skor_etika',
        'skor_justifikasi',
        'skor_rutinitas_bimbingan',
        'skor_progres',
        'rata_rata_f6',
        'catatan_dosen',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'skor_kesesuaian_judul' => 'float',
            'skor_tata_bahasa' => 'float',
            'skor_format_laporan' => 'float',
            'skor_pengetahuan' => 'float',
            'skor_tanya_jawab' => 'float',
            'skor_etika' => 'float',
            'skor_justifikasi' => 'float',
            'skor_rutinitas_bimbingan' => 'float',
            'skor_progres' => 'float',
            'rata_rata_f6' => 'float',
            'submitted_at' => 'datetime',
        ];
    }

    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_magang_id');
    }
}
