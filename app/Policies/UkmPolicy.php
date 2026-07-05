<?php

namespace App\Policies;

use App\Models\Ukm;
use App\Models\User;

class UkmPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function create(User $user): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function update(User $user, Ukm $ukm): bool
    {
        return $user->canManageWebsiteKonten();
    }

    public function delete(User $user, Ukm $ukm): bool
    {
        return $user->canManageWebsiteKonten();
    }
}
