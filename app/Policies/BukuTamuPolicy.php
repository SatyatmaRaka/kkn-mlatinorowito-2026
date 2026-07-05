<?php

namespace App\Policies;

use App\Models\BukuTamu;
use App\Models\User;

class BukuTamuPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, BukuTamu $bukuTamu): bool
    {
        return $user->canReviewLogbook();
    }

    public function delete(User $user, BukuTamu $bukuTamu): bool
    {
        return $user->canReviewLogbook();
    }
}
