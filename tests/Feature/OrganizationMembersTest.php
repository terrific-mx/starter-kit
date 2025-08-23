<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationInvitation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use App\Notifications\OrganizationInvitation as OrganizationInvitationNotification;

uses(RefreshDatabase::class);

it('nullifies currentOrganization when a member is removed from their current organization', function () {
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $member = User::factory()->create();

    $organization->addMember($member);
    $member->switchOrganization($organization);
    expect($organization->is($member->currentOrganization))->toBeTrue();

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->call('removeMember', $member)
        ->assertHasNoErrors();

    $member->refresh();
    expect($member->currentOrganization)->toBeNull();
});

it('allows the owner to remove a member from their organization', function () {
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $member = User::factory()->create();
    $organization->members()->attach($member);

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->call('removeMember', $member)
        ->assertHasNoErrors();

    expect($organization->members()->find($member->id))->toBeNull();
});

it('forbids removing members from another organization', function () {
    $owner = User::factory()->withPersonalOrganization()->create();
    $organizationA = Organization::factory()->for($owner)->create();
    $organizationB = Organization::factory()->create();
    $member = User::factory()->create();
    $organizationB->members()->attach($member);

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organizationA])
        ->call('removeMember', $member)
        ->assertForbidden();
});

it('forbids non-owners from removing organization members', function () {
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $nonOwner = User::factory()->create();
    $member = User::factory()->create();
    $organization->members()->attach($member);

    Volt::actingAs($nonOwner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->call('removeMember', $member)
        ->assertForbidden();
});

it('invites a member to an organization by email', function () {
    Notification::fake();
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $inviteEmail = 'invitee@example.com';

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->set('email', $inviteEmail)
        ->call('sendInvitation')
        ->assertHasNoErrors();

    Notification::assertSentOnDemand(
        OrganizationInvitationNotification::class,
        function ($notification, $channels, $notifiable) use ($inviteEmail) {
            return $notifiable->routes['mail'] === $inviteEmail;
        }
    );

    $invitation = $organization->invitations()->first();
    expect($invitation)->not->toBeNull();
    expect($invitation->email)->toBe($inviteEmail);
});

it('requires an email to invite a member', function () {
    Notification::fake();
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->set('email', '')
        ->call('sendInvitation')
        ->assertHasErrors(['email' => 'required']);
});

it('requires the email to be unique for the organization', function () {
    Notification::fake();
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $inviteEmail = 'invitee@example.com';

    OrganizationInvitation::factory()->for($organization)->create([
        'email' => $inviteEmail,
    ]);

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->set('email', $inviteEmail)
        ->call('sendInvitation')
        ->assertHasErrors(['email' => 'unique']);
});

it('forbids non-owners from sending organization invitations', function () {
    Notification::fake();
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $nonOwner = User::factory()->withPersonalOrganization()->create();
    $inviteEmail = 'invitee@example.com';

    Volt::actingAs($nonOwner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->set('email', $inviteEmail)
        ->call('sendInvitation')
        ->assertForbidden();
});

it('allows the owner to revoke a pending invitation', function () {
    $owner = User::factory()->create();
    $organization = Organization::factory()->for($owner)->create();
    $inviteEmail = 'invitee@example.com';
    $invitation = OrganizationInvitation::factory()->for($organization)->create([
        'email' => $inviteEmail,
    ]);

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->call('revokeInvitation', $invitation)
        ->assertHasNoErrors();

    expect($organization->invitations()->find($invitation->id))->toBeNull();
});

it('forbids non-owners from revoking invitations', function () {
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $nonOwner = User::factory()->withPersonalOrganization()->create();
    $invitation = OrganizationInvitation::factory()->for($organization)->create();

    Volt::actingAs($nonOwner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->call('revokeInvitation', $invitation)
        ->assertForbidden();
});
