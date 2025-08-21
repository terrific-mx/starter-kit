<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationInvitation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use App\Notifications\OrganizationInvitation;

uses(RefreshDatabase::class);

test('an organization owner can invite a member by email using the organization invite volt component', function () {
    Notification::fake();
    $owner = User::factory()->create();
    $organization = Organization::factory()->for($owner)->create();
    $inviteEmail = 'invitee@example.com';

    Volt::actingAs($owner)
        ->test('organizations.invite')
        ->set('email', $inviteEmail)
        ->call('sendInvitation')
        ->assertHasNoErrors();

    Notification::assertSentTo(
        new class($inviteEmail) {
            public $email;
            public function __construct($email) { $this->email = $email; }
            public function routeNotificationForMail() { return $this->email; }
        },
        OrganizationInvitation::class
    );

    $invitation = $organization->invitations()->first();
    expect($invitation)->not->toBeNull();
    expect($invitation->email)->toBe($inviteEmail);
});
