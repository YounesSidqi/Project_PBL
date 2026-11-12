<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $student_id
 * @property int $dosen_id
 * @property int|null $recommended_dosen_id
 * @property int|null $plotted_by
 * @property Carbon|null $plotted_at
 * @property string|null $company_name
 * @property string|null $internship_title
 * @property string $status
 * @property string|null $surat_pernyataan_path
 * @property string|null $surat_pernyataan_name
 * @property string|null $signature_hash
 * @property Carbon|null $approved_at
 * @property string|null $notes
 * @property string|null $revision_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $student
 * @property-read User $dosen
 * @property-read User|null $recommendedDosen
 * @property-read User|null $plotter
 */
class F1Application extends Model
{
    use HasFactory;

    public const STATUS_PENDING_ADMIN = 'pending_admin';

    public const STATUS_PENDING_KPS = 'pending_kps';

    public const STATUS_REVISION_REQUESTED = 'revision_requested';

    public const STATUS_APPROVED = 'approved';

    protected $table = 'form_f1_pengajuan';

    protected $fillable = [
        'student_id',
        'dosen_id',
        'recommended_dosen_id',
        'plotted_by',
        'plotted_at',
        'company_name',
        'internship_title',
        'status',
        'surat_pernyataan_path',
        'surat_pernyataan_name',
        'signature_hash',
        'approved_at',
        'notes',
        'revision_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'plotted_at' => 'datetime',
    ];

    /**
     * Mahasiswa yang mengajukan Form F1.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Dosen yang dipilih/diplot sebagai pembimbing.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Dosen yang direkomendasikan oleh mahasiswa pada Fase 1.
     */
    public function recommendedDosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recommended_dosen_id');
    }

    /**
     * Admin yang melakukan plotting dosen pada Fase 2.
     */
    public function plotter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'plotted_by');
    }

    /**
     * Scope query untuk mengisolasi pengajuan berdasarkan jurusan Admin.
     */
    public function scopeForAdmin(Builder $query, ?User $admin): Builder
    {
        if (! $admin || ! $admin->effective_jurusan) {
            return $query;
        }

        $jurusan = $admin->effective_jurusan;

        return $query->whereHas('student', function (Builder $studentQuery) use ($jurusan): void {
            $studentQuery->where('jurusan', $jurusan);
        });
    }
}
