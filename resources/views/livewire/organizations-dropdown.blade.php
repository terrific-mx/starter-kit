<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use App\Models\Organization;

new class extends Component {
    public $currentOrganization;
    public $organizations;

    // Computed property for the authenticated user
    #[Computed]
    public function user() {
        return Auth::user();
    }

    public function mount()
    {
        $this->currentOrganization = $this->user->currentOrganization;
        $this->organizations = $this->user->organizations;
    }

    public function switchOrganization(\App\Models\Organization $organization)
    {
        if (! $this->user->organizations->contains($organization)) {
            abort(403);
        }
        $this->user->switchOrganization($organization);
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
