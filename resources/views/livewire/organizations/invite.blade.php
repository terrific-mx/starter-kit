<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $email = '';
    public Organization $organization;

    public function sendInvitation()
    {
        $invitation = OrganizationInvitation::create([
            'organization_id' => $this->organization->id,
            'email' => $this->email,
        ]);

        \Notification::route('mail', $this->email)
            ->notify(new \App\Notifications\OrganizationInvitation());
    }
}; ?>

<div>
    //
</div>
