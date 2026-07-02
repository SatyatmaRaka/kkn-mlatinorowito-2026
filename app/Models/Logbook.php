<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'anggota_id',
        'tanggal',
        'judul',
        'deskripsi',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'foto',
        'status',
        'catatan_reviewer',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isEditableBy(User $user): bool
    {
        if ($user->isAdmin() || $user->isKoordinator()) {
            return true;
        }

        return $this->user_id === $user->id && in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED], true);
    }
}
