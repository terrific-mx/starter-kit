<?php

use App\Models\Organization;
use App\Models\User;
use Livewire\Volt\Volt;

it('an authenticated user can edit their organization name', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->for($user)
        ->create([
            'name' => 'Old Name',
        ]);

    Volt::actingAs($user)
        ->test('organizations.edit', ['organization' => $organization])
        ->set('name', 'New Name')
        ->call('edit')
        ->assertHasNoErrors();

    expect($organization->fresh()->name)->toBe('New Name');
});
