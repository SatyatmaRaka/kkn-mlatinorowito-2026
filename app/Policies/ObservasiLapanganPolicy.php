<?php

namespace App\Policies;

use App\Models\ObservasiLapangan;
use App\Models\User;

class ObservasiLapanganPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ObservasiLapangan $observasiLapangan): bool
    {
        return true;
    }

    public function update(User $user, ObservasiLapangan $observasiLapangan): bool
    {
        return true;
    }

    public function delete(User $user, ObservasiLapangan $observasiLapangan): bool
    {
        return $user->canReviewLogbook();
    }
}
