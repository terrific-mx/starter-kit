<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<flux:dropdown position="top" align="start">
    <flux:profile
        :name="auth()->user()->currentOrganization?->name ?? __('No organization')"
    />
    <flux:menu>
        <flux:menu.radio.group>
            @foreach(auth()->user()->organizations as $organization)
                <flux:menu.radio
                    :checked="auth()->user()->currentOrganization->is($organization)"
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
