<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationInvitation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use App\Notifications\OrganizationInvitation as OrganizationInvitationNotification;

uses(RefreshDatabase::class);

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

it('allows the owner to revoke a pending invitation', function () {
    $owner = User::factory()->withPersonalOrganization()->create();
    $organization = Organization::factory()->for($owner)->create();
    $inviteEmail = 'invitee@example.com';
    $invitation = OrganizationInvitation::factory()->for($organization)->create([
        'email' => $inviteEmail,
    ]);

    Volt::actingAs($owner)
        ->test('organizations.settings.members', ['organization' => $organization])
        ->call('revokeInvitation', $invitation->id)
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
        ->call('revokeInvitation', $invitation->id)
        ->assertForbidden();
});
