<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $currentOrganization;
    public $organizations;

    public function mount()
    {
        $user = Auth::user();
        $this->currentOrganization = $user->currentOrganization;
        $this->organizations = $user->organizations;
    }

    public function switchOrganization($organizationId)
    {
        $user = Auth::user();
        $organization = $user->organizations->firstWhere('id', $organizationId);
        if (! $organization) {
            abort(403);
        }
        $user->switchOrganization($organization);
        $this->currentOrganization = $organization;
    }
}; ?>

<flux:dropdown position="top" align="start">
    <flux:profile
        :name="$currentOrganization?->name ?? __('No organization')"
    />
    <flux:menu>
        <flux:menu.radio.group>
            @foreach($organizations as $organization)
                <flux:menu.radio
                    :checked="$currentOrganization->is($organization)"
                >
                    {{ $organization->name }}
                </flux:menu.radio>
            @endforeach
        </flux:menu.radio.group>
        <flux:menu.separator />
        <flux:modal.trigger name="create-organization">
            <flux:menu.item icon="plus">{{ __('New organization') }}</flux:menu.item>
        </flux:modal.trigger>
    </flux:menu>
</flux:dropdown>
