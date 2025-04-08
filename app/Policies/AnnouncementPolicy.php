<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;

class AnnouncementPolicy
{
    public function create(User $user)
    {
        return $user->isRecruiter();
    }

    public function update(User $user, Announcement $announcement)
    {
        return $user->id === $announcement->user_id;
    }

    public function delete(User $user, Announcement $announcement)
    {
        return $user->id === $announcement->user_id;
    }
}
