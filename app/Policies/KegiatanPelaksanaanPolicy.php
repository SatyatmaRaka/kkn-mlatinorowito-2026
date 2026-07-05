<?php

namespace App\Policies;

use App\Models\KegiatanPelaksanaan;
use App\Models\User;

class KegiatanPelaksanaanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, KegiatanPelaksanaan $kegiatanPelaksanaan): bool
    {
        return true;
    }

    public function update(User $user, KegiatanPelaksanaan $kegiatanPelaksanaan): bool
    {
        return $user->canReviewLogbook();
    }

    public function delete(User $user, KegiatanPelaksanaan $kegiatanPelaksanaan): bool
    {
        return $user->canReviewLogbook();
    }
}
