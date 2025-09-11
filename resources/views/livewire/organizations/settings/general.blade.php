<?php

use Livewire\Volt\Component;

use App\Models\Organization;
use Flux\Flux;

new class extends Component {
    public Organization $organization;

    public string $name;

    public function mount()
    {
        $this->authorize('update', $this->organization);

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

<x-slot:breadcrumbs>
    @include('partials.organization-settings-breadcrumbs', ['organization' => $organization, 'current' => __('General')])
</x-slot:breadcrumbs>

<div>
    @include('partials.organization-settings-heading')
    <div class="flex flex-col lg:flex-row gap-8">
        @include('partials.organization-settings-sidebar', ['organization' => $organization])
        <div class="flex-1">
            <header>
                <flux:heading size="lg">
                    {{ __('General Settings') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ __('Update your organization name below to keep your workspace up to date.') }}
                </flux:text>
            </header>
            <form wire:submit="edit" class="max-w-lg space-y-6 mt-6">
                <flux:input wire:model="name" :label="__('Name')" />
                <flux:button type="submit" variant="primary">
                    {{ __('Save') }}
                </flux:button>
            </form>
        </div>
    </div>
</div>
