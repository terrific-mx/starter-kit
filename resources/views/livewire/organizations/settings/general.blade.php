<?php

use Livewire\Volt\Component;

use App\Models\Organization;
use Flux\Flux;

new class extends Component {
    public Organization $organization;

    public string $name;

    public function mount()
    {
        $this->name = $this->organization->name;
    }

    public function edit()
    {
        $this->authorize('update', $this->organization);

        $this->validate([
            'name' => ['required'],
        ]);

        $this->organization->update(['name' => $this->name]);

        Flux::toast(
            heading: __('Saved'),
            text: __('Organization updated successfully.'),
            variant: 'success'
        );
    }
}; ?>

<div>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('home') }}">{{ __('Home') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organizations.show', $organization) }}">{{ $organization->name }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organizations.settings.general', $organization) }}">{{ __('Settings') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ __('General') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <div class="mb-8">
        <flux:heading size="lg">
            {{ __('Organization Settings') }}
        </flux:heading>
        <flux:text class="mt-1">
            {{ __('Manage all aspects of your organization, including general details and members.') }}
        </flux:text>
    </div>
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-64 lg:shrink-0 mb-8 lg:mb-0">
            <flux:navlist>
                <flux:navlist.item
                    icon="cog-6-tooth"
                    :href="route('organizations.settings.general', $organization)"
                    :current="request()->routeIs('organizations.settings.general')"
                    wire:navigate
                >
                    {{ __('General') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="layout-grid"
                    :href="route('organizations.settings.members', $organization)"
                    :current="request()->routeIs('organizations.settings.members')"
                    wire:navigate
                >
                    {{ __('Members') }}
                </flux:navlist.item>
            </flux:navlist>
        </div>
        <div class="flex-1">
            <div class="mb-6">
                <flux:heading size="lg">
                    {{ __('General Settings') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ __('Update your organization name below to keep your workspace up to date.') }}
                </flux:text>
            </div>
            <form wire:submit="edit" class="max-w-lg space-y-6">
                <flux:input wire:model="name" :label="__('Name')" />
                <flux:button type="submit" variant="primary">
                    {{ __('Save') }}
                </flux:button>
            </form>
        </div>
    </div>

</div>
