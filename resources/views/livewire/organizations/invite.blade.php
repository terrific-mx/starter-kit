<?php

use Livewire\Volt\Component;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationInvitation as OrganizationInvitationNotification;
use Illuminate\Validation\Rule;

new class extends Component {
    public Organization $organization;

    public string $email = '';

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('organization_invitations', 'email')->where(
                    fn ($query) => $query->where('organization_id', $this->organization->id)
                ),
            ],
        ];
    }

    public function sendInvitation()
    {
        $this->authorize('invite', $this->organization);

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
