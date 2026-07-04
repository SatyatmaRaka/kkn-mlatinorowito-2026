<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\User;

/**
 * Kebijakan otorisasi data anggota KKN (panel admin).
 */
class AnggotaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function create(User $user): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function update(User $user, Anggota $anggota): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function delete(User $user, Anggota $anggota): bool
    {
        return $user->canManageWebsiteKonten();
    }
}
