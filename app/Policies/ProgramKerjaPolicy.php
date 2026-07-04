<?php

namespace App\Policies;

use App\Models\ProgramKerja;
use App\Models\User;

/**
 * Kebijakan otorisasi program kerja KKN (panel admin).
 */
class ProgramKerjaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function create(User $user): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function update(User $user, ProgramKerja $programKerja): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function delete(User $user, ProgramKerja $programKerja): bool
    {
        return $user->canManageWebsiteKonten();
    }
}
