<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;

new class extends Component {
    public string $name = '';

    public function create()
    {
        $this->authorize('create', Organization::class);

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Auth::user()->organizations()->create([
            'name' => $this->name,
        ]);
    }
}; ?>

<flux:modal name="create-organization" class="md:w-96">
    <form wire:submit="create" class="space-y-6">
        <div>
            <flux:heading size="lg">Create Organization</flux:heading>
            <flux:text class="mt-2">Enter a name for your new organization.</flux:text>
        </div>
        <flux:input label="Organization Name" placeholder="Acme Inc" wire:model="name" />

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost" type="button">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary">Create</flux:button>
        </div>
    </form>
</flux:modal>
