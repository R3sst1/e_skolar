<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function view(User $user, Application $application): bool
    {
        return $user->id === $application->user_id || 
               $user->isSuperAdmin() || 
               $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isApplicant();
    }

    public function update(User $user, Application $application): bool
    {
        return $user->id === $application->user_id && $application->isPending();
    }

    public function cancel(User $user, Application $application): bool
    {
        return $user->id === $application->user_id && $application->isPending();
    }

    public function review(User $user, Application $application): bool
    {
        return ($user->isSuperAdmin() || $user->isAdmin()) && $application->isPending();
    }

    public function approve(User $user, Application $application): bool
    {
        return ($user->isSuperAdmin() || $user->isAdmin()) && $application->isUnderReview();
    }

    public function reject(User $user, Application $application): bool
    {
        return ($user->isSuperAdmin() || $user->isAdmin()) && $application->isUnderReview();
    }
}
