<?php

use Livewire\Volt\Component;

use App\Models\Organization;

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
    }
}; ?>

<div>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('home') }}">{{ __('Home') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ __('Edit Organization') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <div class="mb-6">
        <flux:heading level="1" class="mb-2">
            {{ __('Edit Organization') }}
        </flux:heading>
        <flux:text>
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
