<?php

namespace App\Policies;

use App\Models\User;

class OrganizationPolicy
{
    /**
     * Create a new policy instance.
     */
    /**
     * Determine whether the user can create organizations.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can switch to the given organization.
     */
    public function switch(User $user, \App\Models\Organization $organization): bool
    {
        return $user->organizations->contains($organization);
    }

    public function __construct()
    {
        //
    }
}
