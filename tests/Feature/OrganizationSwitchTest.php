<?php

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

// User can switch to another organization they belong to
it('allows a user to switch to another organization they belong to', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $user->organizations()->attach([$orgA->id, $orgB->id]);
    Auth::login($user);
    $user->currentOrganization()->associate($orgA)->save();

    $response = $this->post(route('organizations.switch'), ['organization_id' => $orgB->id]);

    $response->assertRedirect(route('dashboard'));
    $user->refresh();
    expect($user->currentOrganization->is($orgB))->toBeTrue();
});

// User cannot switch to an organization they do not belong to
it('prevents switching to an organization the user does not belong to', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $user->organizations()->attach($orgA->id);
    Auth::login($user);
    $user->currentOrganization()->associate($orgA)->save();

    $response = $this->post(route('organizations.switch'), ['organization_id' => $orgB->id]);

    $response->assertForbidden();
    $user->refresh();
    expect($user->currentOrganization->is($orgA))->toBeTrue();
});

// Switching to the same organization does not change anything
it('does nothing when switching to the same organization', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $user->organizations()->attach($orgA->id);
    Auth::login($user);
    $user->currentOrganization()->associate($orgA)->save();

    $response = $this->post(route('organizations.switch'), ['organization_id' => $orgA->id]);

    $response->assertRedirect(route('dashboard'));
    $user->refresh();
    expect($user->currentOrganization->is($orgA))->toBeTrue();
});

// Flash message after switching
it('flashes a message after switching organizations', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $user->organizations()->attach([$orgA->id, $orgB->id]);
    Auth::login($user);
    $user->currentOrganization()->associate($orgA)->save();

    $response = $this->post(route('organizations.switch'), ['organization_id' => $orgB->id]);

    $response->assertSessionHas('status', __('Organization switched successfully'));
});
