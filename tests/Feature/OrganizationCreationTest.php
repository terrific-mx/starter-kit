<?php

use Livewire\Volt\Volt;
use App\Models\User;
use App\Models\Organization;
use function Pest\Laravel\actingAs;

it('creates a new organization for the user with valid data', function () {
    $user = User::factory()->create();
    actingAs($user);

    $orgName = 'Acme Inc';
    $response = Volt::test('organizations.create')
        ->set('name', $orgName)
        ->call('create');

    $response->assertHasNoErrors();
    expect($user->organizations()->where('name', $orgName)->exists())->toBeTrue();
});

it('shows validation errors for missing or invalid organization name', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = Volt::test('organizations.create')
        ->set('name', '')
        ->call('create');

    $response->assertHasErrors(['name']);
});

it('guests cannot create organizations', function () {
    $orgName = 'Gamma Ltd';
    $response = Volt::test('organizations.create')
        ->set('name', $orgName)
        ->call('create');

    $response->assertForbidden();
});
