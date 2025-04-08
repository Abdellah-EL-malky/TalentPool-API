<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Application;

class ApplicationPolicy
{
    public function updateStatus(User $user, Application $application)
    {
        return $user->isRecruiter() &&
            $user->id === $application->announcement->user_id;
    }

    public function delete(User $user, Application $application)
    {
        return $user->id === $application->user_id;
    }
}
