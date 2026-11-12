<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magangs';

    protected $fillable = [
        'mahasiswa_id',
        'industri_id',
        'dosen_id',
        'nama_perusahaan_surat',
        'alamat_perusahaan_surat',
        'judul_magang',
        'posisi_magang',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_surat_pengantar',
        'status_penempatan',
        'status_dosen',
        'nomor_surat_pengantar',
        'file_surat_pengantar',
        'file_surat_keterangan',
        'nilai_akhir_fuzzy',
        'fuzzy_score_num',
        'fuzzy_percentage',
        'catatan_penolakan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'fuzzy_score_num' => 'float',
            'fuzzy_percentage' => 'float',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function industri()
    {
        return $this->belongsTo(Industri::class, 'industri_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function tugasF2()
    {
        return $this->hasMany(TugasF2::class, 'pengajuan_magang_id');
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'pengajuan_magang_id');
    }

    public function bimbingans()
    {
        return $this->hasMany(BimbinganF4::class, 'pengajuan_magang_id');
    }

    public function evaluasiF5()
    {
        return $this->hasOne(EvaluasiF5::class, 'pengajuan_magang_id');
    }

    public function evaluasiF6()
    {
        return $this->hasOne(EvaluasiF6::class, 'pengajuan_magang_id');
    }

    public function getNamaPerusahaanAttribute(): ?string
    {
        return $this->industri?->nama_perusahaan ?? $this->nama_perusahaan_surat;
    }

    public function getAlamatPerusahaanAttribute(): ?string
    {
        return $this->industri?->alamat ?? $this->alamat_perusahaan_surat;
    }

    /**
     * Status verifikasi tempat PKL efektif (menggabungkan status_verifikasi industri & status_penempatan mahasiswa).
     */
    public function getStatusVerifikasiEfektifAttribute(): ?string
    {
        if (! $this->industri) {
            return null;
        }

        if ($this->industri->status_verifikasi !== 'acc') {
            return $this->industri->status_verifikasi;
        }

        return $this->status_penempatan ?? 'acc';
    }

    public function getKotaPerusahaanAttribute(): ?string
    {
        return $this->industri?->kota;
    }

    public function getMentorIndustriAttribute(): ?string
    {
        return $this->industri?->kontak_person;
    }

    /**
     * Scope query untuk mengisolasi pengajuan magang berdasarkan jurusan Admin.
     */
    public function scopeForAdmin(Builder $query, ?User $admin): Builder
    {
        if (! $admin || ! $admin->effective_jurusan) {
            return $query;
        }

        $jurusan = $admin->effective_jurusan;

        return $query->whereHas('mahasiswa', function (Builder $studentQuery) use ($jurusan): void {
            $studentQuery->where('jurusan', $jurusan);
        });
    }
}
