<?php

use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can switch to another organization they belong to', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $user->organizations()->attach([$orgA->id, $orgB->id]);
    $user->currentOrganization()->associate($orgA)->save();

    $this->actingAs($user)
        ->post(route('organizations.switch'), ['organization_id' => $orgB->id])
        ->assertRedirect(route('dashboard'));

    $user->refresh();
    expect($user->currentOrganization->is($orgB))->toBeTrue();
});

test('user cannot switch to an organization they do not belong to', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $user->organizations()->attach($orgA->id);
    $user->currentOrganization()->associate($orgA)->save();

    $this->actingAs($user)
        ->post(route('organizations.switch'), ['organization_id' => $orgB->id])
        ->assertForbidden();

    $user->refresh();
    expect($user->currentOrganization->is($orgA))->toBeTrue();
});

test('dropdown only shows organizations user belongs to', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $orgC = Organization::factory()->create();
    $user->organizations()->attach([$orgA->id, $orgB->id]);
    $user->currentOrganization()->associate($orgA)->save();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSee($orgA->name)
        ->assertSee($orgB->name)
        ->assertDontSee($orgC->name);
});

test('current organization is reflected in UI after switch', function () {
    $user = User::factory()->create();
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();
    $user->organizations()->attach([$orgA->id, $orgB->id]);
    $user->currentOrganization()->associate($orgA)->save();

    $this->actingAs($user)
        ->post(route('organizations.switch'), ['organization_id' => $orgB->id]);

    $this->get(route('dashboard'))
        ->assertSee($orgB->name);
});
