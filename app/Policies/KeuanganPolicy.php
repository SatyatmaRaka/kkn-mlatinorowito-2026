<?php

namespace App\Policies;

use App\Models\Keuangan;
use App\Models\User;

/**
 * Kebijakan otorisasi modul keuangan KKN.
 */
class KeuanganPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageKeuangan();
    }

    public function create(User $user): bool
    {
        return $user->canManageKeuangan();
    }

    public function update(User $user, Keuangan $keuangan): bool
    {
        return $user->canManageKeuangan();
    }

    public function delete(User $user, Keuangan $keuangan): bool
    {
        return $user->canManageKeuangan();
    }

    public function export(User $user): bool
    {
        return $user->canManageKeuangan();
    }
}
