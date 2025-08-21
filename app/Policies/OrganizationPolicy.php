<?php

namespace App\Policies;

use App\Models\Organization;
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
    public function switch(User $user, Organization $organization): bool
    {
        return $user->organizations->contains($organization);
    }

    /**
     * Determine whether the user can update the organization.
     */
    public function update(User $user, Organization $organization): bool
    {
        return $organization->user->is($user);
    }

    /**
     * Determine whether the user can invite members to the organization.
     */
    public function invite(User $user, Organization $organization): bool
    {
        return $organization->user->is($user);
    }

    public function __construct()
    {
        //
    }
}
