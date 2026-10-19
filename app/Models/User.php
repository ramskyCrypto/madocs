<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_KEPALA_SEKOLAH = 'kepala_sekolah';
    public const ROLE_GURU = 'guru';
    public const ROLE_STAFF = 'staff';
    public const ROLE_SISWA = 'siswa';

    public const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah',
        self::ROLE_GURU => 'Guru',
        self::ROLE_STAFF => 'Staff / Tata Usaha',
        self::ROLE_SISWA => 'Siswa',
    ];

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /**
     * Role yang boleh mengelola dokumen milik siapa saja (bukan hanya miliknya).
     */
    public function canManageAllDocuments(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_STAFF], true);
    }

    public function canManageCategories(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_STAFF], true);
    }

    public function canManageUsers(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function canUploadDocuments(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_GURU, self::ROLE_STAFF], true);
    }
}
