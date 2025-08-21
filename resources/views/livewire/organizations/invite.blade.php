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
            ->notify(new OrganizationInvitationNotification($invitation));
    }
}; ?>

<div class="space-y-6">
    <flux:heading size="lg">{{ __('Invite Member') }}</flux:heading>

    <form wire:submit="sendInvitation" class="max-w-lg space-y-4">
        <flux:field>
            <flux:label>{{ __('Email address') }}</flux:label>
            <flux:input.group>
                <flux:input
                    type="email"
                    wire:model="email"
                    placeholder="{{ __('Enter member email') }}"
                     icon="user"
                />
                <flux:button type="submit">
                    {{ __('Send Invitation') }}
                </flux:button>
            </flux:input.group>
            <flux:error name="email" />
        </flux:field>
    </form>
</div>
