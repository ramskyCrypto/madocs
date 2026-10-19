<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_ARSIP = 'arsip';

    public const STATUSES = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_ARSIP => 'Arsip',
    ];

    public const VISIBILITY_PUBLIK = 'publik';
    public const VISIBILITY_INTERNAL = 'internal';
    public const VISIBILITY_PRIVAT = 'privat';

    public const VISIBILITIES = [
        self::VISIBILITY_PUBLIK => 'Publik (semua termasuk siswa)',
        self::VISIBILITY_INTERNAL => 'Internal (staf sekolah)',
        self::VISIBILITY_PRIVAT => 'Privat (pemilik & admin)',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'document_number',
        'document_date',
        'description',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'file_extension',
        'status',
        'visibility',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function humanFileSize(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }

    public function isPdf(): bool
    {
        return $this->file_extension === 'pdf';
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('document_number', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"));
        });
    }

    /**
     * Batasi query dokumen sesuai visibilitas yang boleh dilihat oleh user.
     * Ini adalah lapisan keamanan tambahan di level query, di luar Policy,
     * supaya listing dan pencarian tidak pernah menampilkan dokumen yang
     * tidak boleh dilihat role tersebut.
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->role === User::ROLE_SISWA) {
            return $query->where('visibility', self::VISIBILITY_PUBLIK);
        }

        if ($user->role === User::ROLE_KEPALA_SEKOLAH || $user->role === User::ROLE_GURU) {
            // Dibungkus dalam satu closure supaya kondisi ini tetap jadi satu
            // grup OR yang utuh, sehingga filter/pencarian lain yang dirantai
            // setelah scope ini (category_id, status, q, dst.) tetap berlaku
            // sebagai AND terhadap keseluruhan grup, bukan "lolos" lewat OR.
            return $query->where(function ($q) use ($user) {
                $q->whereIn('visibility', [self::VISIBILITY_PUBLIK, self::VISIBILITY_INTERNAL])
                    ->orWhere('user_id', $user->id);
            });
        }

        // admin & staff dapat melihat semua
        return $query;
    }
}
