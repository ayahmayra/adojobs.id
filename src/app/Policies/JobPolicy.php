<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    /**
     * Determine whether the user can view the job.
     */
    public function view(User $user, Job $job): bool
    {
        return $user->isEmployer() && $job->employer_id === $user->employer->id;
    }

    /**
     * Determine whether the user can update the job.
     */
    public function update(User $user, Job $job): bool
    {
        return $user->isEmployer() && $job->employer_id === $user->employer->id;
    }

    /**
     * Determine whether the user can delete the job.
     */
    public function delete(User $user, Job $job): bool
    {
        return $user->isEmployer() && $job->employer_id === $user->employer->id;
    }
}

