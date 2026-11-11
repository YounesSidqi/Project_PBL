<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiF5 extends Model
{
    use HasFactory;

    protected $table = 'form_f5_evaluasi';

    protected $fillable = [
        'pengajuan_magang_id',
        'skor_komunikasi',
        'skor_berpikir_kritis',
        'skor_kerjasama',
        'skor_inisiatif',
        'skor_literasi',
        'skor_deskripsi_produk',
        'skor_spesifikasi',
        'skor_desain',
        'skor_implementasi',
        'skor_pengujian',
        'rata_rata_f5',
        'saran_industri',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'skor_komunikasi' => 'float',
            'skor_berpikir_kritis' => 'float',
            'skor_kerjasama' => 'float',
            'skor_inisiatif' => 'float',
            'skor_literasi' => 'float',
            'skor_deskripsi_produk' => 'float',
            'skor_spesifikasi' => 'float',
            'skor_desain' => 'float',
            'skor_implementasi' => 'float',
            'skor_pengujian' => 'float',
            'rata_rata_f5' => 'float',
            'submitted_at' => 'datetime',
        ];
    }

    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_magang_id');
    }
}
