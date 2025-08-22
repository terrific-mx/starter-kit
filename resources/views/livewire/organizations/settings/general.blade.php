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
    @include('partials.organization-settings-breadcrumbs', ['organization' => $organization, 'current' => __('General')])
    @include('partials.organization-settings-heading')
    <div class="flex flex-col lg:flex-row gap-8">
        @include('partials.organization-settings-sidebar', ['organization' => $organization])
        <div class="flex-1">
            <div class="mb-8">
                <flux:heading size="lg">
                    {{ __('Organization Members') }}
                </flux:heading>
                <flux:text class="mt-1 mb-4">
                    {{ __('These are all the members in your organization.') }}
                </flux:text>
                <table class="min-w-full border rounded-lg overflow-hidden">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">{{ __('Name') }}</th>
                            <th class="px-4 py-2 text-left font-semibold">{{ __('Email') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organization->users as $member)
                            <tr>
                                <td class="px-4 py-2">{{ $member->name }}</td>
                                <td class="px-4 py-2">{{ $member->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
