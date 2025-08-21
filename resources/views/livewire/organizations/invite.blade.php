<?php

use Livewire\Volt\Component;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationInvitation as OrganizationInvitationNotification;

new class extends Component {
    public Organization $organization;

    public string $email = '';

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'unique:organization_invitations,email,NULL,id,organization_id,' . $this->organization->id,
            ],
        ];
    }

    public function sendInvitation()
    {
        $this->validate();

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
