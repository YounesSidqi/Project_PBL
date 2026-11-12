<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $identifier
 * @property string|null $prodi
 * @property string|null $jurusan
 * @property string|null $phone
 * @property int|null $dosen_id
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $effective_jurusan
 * @property-read User|null $dosen
 * @property-read Collection<int, User> $students
 * @property-read Collection<int, Logbook> $logbooks
 * @property-read Collection<int, Logbook> $mentoredLogbooks
 */
#[Fillable(['name', 'email', 'password', 'role', 'identifier', 'prodi', 'jurusan', 'phone', 'dosen_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Daftar kode jurusan yang didukung sistem.
     *
     * @var array<int, string>
     */
    public const JURUSAN_LIST = ['TI', 'TMJ', 'TMD', 'RKS'];

    /**
     * Sinkronisasi otomatis kode jurusan saat model disimpan.
     */
    protected static function booted(): void
    {
        static::saving(function (self $user): void {
            $resolved = self::resolveJurusanCode($user->jurusan, $user->prodi);

            if ($resolved !== null) {
                $user->jurusan = $resolved;
                if (empty($user->prodi)) {
                    $user->prodi = $resolved;
                }
            } elseif (in_array($user->role, ['admin', 'mahasiswa', 'dosen', 'kps'], true) && empty($user->jurusan)) {
                $user->jurusan = 'TI';
                if (empty($user->prodi)) {
                    $user->prodi = 'TI';
                }
            }
        });
    }

    /**
     * Normalisasi string jurusan / prodi menjadi kode jurusan standar (TI, TMJ, TMD, RKS).
     */
    public static function resolveJurusanCode(?string $jurusan, ?string $prodi = null): ?string
    {
        foreach ([$jurusan, $prodi] as $raw) {
            if (! $raw) {
                continue;
            }

            $upper = strtoupper(trim($raw));
            if (in_array($upper, self::JURUSAN_LIST, true)) {
                return $upper;
            }

            $lower = strtolower(trim($raw));

            if (
                str_contains($lower, 'tmj')
                || str_contains($lower, 'multimedia dan jaringan')
                || str_contains($lower, 'komputer & jaringan')
                || str_contains($lower, 'komputer dan jaringan')
                || str_contains($lower, 'jaringan')
            ) {
                return 'TMJ';
            }

            if (
                str_contains($lower, 'tmd')
                || str_contains($lower, 'multimedia digital')
                || str_contains($lower, 'broadband multimedia')
                || str_contains($lower, 'multimedia')
            ) {
                return 'TMD';
            }

            if (
                str_contains($lower, 'rks')
                || str_contains($lower, 'keamanan siber')
                || str_contains($lower, 'cyber')
            ) {
                return 'RKS';
            }

            if (
                str_contains($lower, 'informatika')
                || preg_match('/\bti\b/i', $raw) === 1
            ) {
                return 'TI';
            }
        }

        return null;
    }

    /**
     * Get kode jurusan efektif user.
     */
    public function getEffectiveJurusanAttribute(): ?string
    {
        return self::resolveJurusanCode($this->jurusan, $this->prodi)
            ?? (in_array($this->role, ['admin', 'mahasiswa', 'dosen', 'kps'], true) ? 'TI' : null);
    }

    /**
     * Periksa apakah user ini berada di jurusan yang sama dengan user lain.
     */
    public function hasSameJurusanAs(?self $other): bool
    {
        if (! $other) {
            return false;
        }

        return $this->effective_jurusan !== null
            && $this->effective_jurusan === $other->effective_jurusan;
    }

    /**
     * Scope query untuk memfilter data berdasarkan jurusan yang sama dengan Admin.
     */
    public function scopeSameJurusan(Builder $query, ?self $admin): Builder
    {
        if (! $admin || ! $admin->effective_jurusan) {
            return $query;
        }

        return $query->where('jurusan', $admin->effective_jurusan);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Dosen pembimbing that mentors this student.
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Students that are mentored by this lecturer.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'dosen_id');
    }

    /**
     * Logbooks written by this student.
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'user_id');
    }

    /**
     * Logbooks to be reviewed by this lecturer.
     */
    public function mentoredLogbooks()
    {
        return $this->hasMany(Logbook::class, 'dosen_id');
    }

    /**
     * Pengajuan F1 yang diajukan oleh mahasiswa ini.
     */
    public function f1Applications()
    {
        return $this->hasMany(F1Application::class, 'student_id');
    }

    /**
     * Pengajuan F1 terbaru mahasiswa.
     */
    public function latestF1Application()
    {
        return $this->hasOne(F1Application::class, 'student_id')->latestOfMany();
    }

    /**
     * Pengajuan F1 mahasiswa yang memilih dosen ini.
     */
    public function incomingF1Applications()
    {
        return $this->hasMany(F1Application::class, 'dosen_id');
    }

    /**
     * Profil industri yang dikelola jika user ini adalah penanggung jawab industri.
     */
    public function industriProfile()
    {
        return $this->hasOne(Industri::class, 'user_id');
    }

    /**
     * Seluruh riwayat pengajuan magang mahasiswa ini.
     */
    public function pengajuanMagangs()
    {
        return $this->hasMany(PengajuanMagang::class, 'mahasiswa_id');
    }

    /**
     * Pengajuan magang aktif mahasiswa.
     */
    public function pengajuanMagangAktif()
    {
        return $this->hasOne(PengajuanMagang::class, 'mahasiswa_id')->latestOfMany();
    }

    /**
     * Check if user is Admin Jurusan.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is KPS (Ketua Program Studi).
     */
    public function isKps(): bool
    {
        return in_array($this->role, ['kps', 'admin']);
    }

    /**
     * Check if user is Dosen.
     */
    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    /**
     * Check if user is Mahasiswa.
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Check if user is Pembimbing Industri (DUDI).
     */
    public function isIndustri(): bool
    {
        return in_array($this->role, ['industri', 'dudi']);
    }

    /**
     * Accessor for student NIM.
     */
    public function getNimAttribute(): ?string
    {
        return $this->identifier;
    }

    /**
     * Accessor for lecturer/staff NIP.
     */
    public function getNipAttribute(): ?string
    {
        return $this->identifier;
    }
}
