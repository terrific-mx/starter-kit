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
    $owner = User::factory()->create();
    $organization = Organization::factory()->for($owner)->create();
    $inviteEmail = 'invitee@example.com';

    Volt::actingAs($owner)
        ->test('organizations.invite', ['organization' => $organization])
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
