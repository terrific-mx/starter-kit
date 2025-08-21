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
    <flux:heading size="xl" level="1" class="mb-2">
        {{ __('Edit Organization') }}
    </flux:heading>
    <flux:text size="lg" class="mb-6">
        {{ __('Update your organization name below to keep your workspace up to date.') }}
    </flux:text>
    <form wire:submit="edit">
        <flux:input wire:model="name" :label="__('Name')" class="mb-4" />
        <flux:button type="submit" variant="primary">
            {{ __('Save') }}
        </flux:button>
    </form>
</div>
