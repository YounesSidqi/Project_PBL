<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
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
 * @property string|null $phone
 * @property int|null $dosen_id
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $dosen
 * @property-read Collection<int, User> $students
 * @property-read Collection<int, Logbook> $logbooks
 * @property-read Collection<int, Logbook> $mentoredLogbooks
 */
#[Fillable(['name', 'email', 'password', 'role', 'identifier', 'prodi', 'phone', 'dosen_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
}
