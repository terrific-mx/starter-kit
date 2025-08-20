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

    public function __construct()
    {
        //
    }
}
