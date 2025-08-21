<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

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
    public function switch(User $user, Organization $organization): bool
    {
        return $user->organizations->contains($organization);
    }

    public function __construct()
    {
        //
    }
}
