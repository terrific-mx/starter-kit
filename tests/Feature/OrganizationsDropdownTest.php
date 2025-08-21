<?php

use App\Models\User;
use App\Models\Organization;
use Livewire\Volt\Volt;

it('allows user to switch organizations from the dropdown', function () {
    $user = User::factory()->has(Organization::factory()->count(2))->create();
    $orgA = $user->organizations->first();
    $orgB = $user->organizations->skip(1)->first();

    $user->switchOrganization($orgA);
    expect($user->fresh()->currentOrganization->is($orgA))->toBeTrue();

    Volt::actingAs($user)
        ->test('organizations-dropdown')
        ->call('switchOrganization', $orgB->id)
        ->assertRedirect(route('dashboard'));

    expect($user->fresh()->currentOrganization->is($orgB))->toBeTrue();
});

it('prevents user from switching to an organization they do not own', function () {
    $user = User::factory()->has(Organization::factory()->count(2))->create();
    $orgA = $user->organizations->first();
    $otherOrg = Organization::factory()->create();

    $user->switchOrganization($orgA);
    expect($user->fresh()->currentOrganization->is($orgA))->toBeTrue();

    Volt::actingAs($user)
        ->test('organizations-dropdown')
        ->call('switchOrganization', $otherOrg->id)
        ->assertForbidden();

    expect($user->fresh()->currentOrganization->is($otherOrg))->toBeFalse();
});
