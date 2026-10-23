<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $student_id
 * @property int $dosen_id
 * @property string|null $company_name
 * @property string|null $internship_title
 * @property string $status
 * @property string|null $surat_pernyataan_path
 * @property string|null $surat_pernyataan_name
 * @property string|null $signature_hash
 * @property Carbon|null $approved_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $student
 * @property-read User $dosen
 */
class F1Application extends Model
{
    use HasFactory;

    protected $table = 'f1_applications';

    protected $fillable = [
        'student_id',
        'dosen_id',
        'company_name',
        'internship_title',
        'status',
        'surat_pernyataan_path',
        'surat_pernyataan_name',
        'signature_hash',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Mahasiswa yang mengajukan Form F1.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Dosen yang dipilih sebagai pembimbing.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
