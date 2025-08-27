<?php

use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('switching to a subscribed organization redirects to dashboard', function () {
    /** @var User $user */
    $user = User::factory()->withPersonalOrganization()->create();

    // Create a subscribed organization
    $subscribedOrg = Organization::factory()->for($user)->withSubscription()->create();

    // Create an unsubscribed organization and set as current
    $unsubscribedOrg = Organization::factory()->for($user)->create();
    $user->switchOrganization($unsubscribedOrg);

    Volt::actingAs($user)->test('billing.subscription-required')
        ->call('switchOrganization', $subscribedOrg)
        ->assertRedirect(route('dashboard'));
});

test('switching to a non-subscribed organization stays on the page', function () {
    /** @var User $user */
    $user = User::factory()->withPersonalOrganization()->create();

    // Create two unsubscribed organizations
    $org1 = Organization::factory()->for($user)->create();
    $org2 = Organization::factory()->for($user)->create();

    // Set current to org1
    $user->switchOrganization($org1);

    Volt::actingAs($user)->test('billing.subscription-required')
        ->call('switchOrganization', $org2)
        ->assertHasNoRedirect()
        ->assertOk();
});

test('user cannot switch to an organization they do not own', function () {
    /** @var User $user */
    $user = User::factory()->withPersonalOrganization()->create();

    // Create another user and their organization
    $otherUser = User::factory()->withPersonalOrganization()->create();
    $otherOrg = $otherUser->organizations->first();

    // Set current to user's own org
    $user->switchOrganization($user->organizations->first());

    Volt::actingAs($user)->test('billing.subscription-required')
        ->call('switchOrganization', $otherOrg)
        ->assertForbidden();
});
