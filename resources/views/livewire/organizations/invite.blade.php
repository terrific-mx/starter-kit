<?php

use Livewire\Volt\Component;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationInvitation as OrganizationInvitationNotification;

new class extends Component {
    public string $email = '';
    public Organization $organization;

    public function sendInvitation()
    {
        $invitation = OrganizationInvitation::create([
            'organization_id' => $this->organization->id,
            'email' => $this->email,
        ]);

        Notification::route('mail', $this->email)
            ->notify(new OrganizationInvitationNotification());
    }
}; ?>

<div>
    //
</div>
