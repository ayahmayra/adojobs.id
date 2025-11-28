<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view the application.
     */
    public function view(User $user, Application $application): bool
    {
        if ($user->isSeeker()) {
            return $application->seeker_id === $user->seeker->id;
        }

        if ($user->isEmployer()) {
            return $application->job->employer_id === $user->employer->id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the application.
     */
    public function update(User $user, Application $application): bool
    {
        return $user->isEmployer() && $application->job->employer_id === $user->employer->id;
    }

    /**
     * Determine whether the user can delete the application.
     */
    public function delete(User $user, Application $application): bool
    {
        return $user->isSeeker() && $application->seeker_id === $user->seeker->id;
    }
}

