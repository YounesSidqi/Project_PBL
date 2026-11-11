<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $dosen_id
 * @property string $subject
 * @property Carbon $tanggal
 * @property string $content
 * @property string $status
 * @property string|null $feedback
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $student
 * @property-read User|null $dosen
 */
class Logbook extends Model
{
    use HasFactory;

    protected $table = 'form_f3_logbook';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'dosen_id',
        'pengajuan_magang_id',
        'subject',
        'tanggal',
        'hari_terlambat',
        'content',
        'status',
        'status_industri',
        'feedback',
        'catatan_industri',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'hari_terlambat' => 'integer',
    ];

    /**
     * Get the student (mahasiswa) that owns the logbook.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the assigned lecturer (dosen pengampu) for this logbook.
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Get the internship application related to this logbook.
     */
    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_magang_id');
    }
}
