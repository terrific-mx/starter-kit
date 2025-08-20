<?php

use Livewire\Volt\Component;

new class extends Component {
    public $currentOrganization;
    public $organizations;

    public function mount()
    {
        $user = auth()->user();
        $this->currentOrganization = $user->currentOrganization;
        $this->organizations = $user->organizations;
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
