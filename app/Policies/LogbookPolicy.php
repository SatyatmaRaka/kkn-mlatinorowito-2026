<?php

namespace App\Policies;

use App\Models\Logbook;
use App\Models\User;

/**
 * Kebijakan otorisasi catatan harian (logbook) KKN.
 */
class LogbookPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->anggota_id !== null;
    }

    public function update(User $user, Logbook $logbook): bool
    {
        return $logbook->isEditableBy($user);
    }

    public function delete(User $user, Logbook $logbook): bool
    {
        return $logbook->isEditableBy($user);
    }

    /** Review/setujui/tolak — hanya koordinator/admin, status submitted. */
    public function review(User $user, Logbook $logbook): bool
    {
        return $user->canReviewLogbook() && $logbook->isReviewable();
    }

    public function export(User $user): bool
    {
        return $user->canReviewLogbook();
    }
}
