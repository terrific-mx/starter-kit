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
        $this->validate([
            'name' => ['required'],
        ]);
        $this->organization->update(['name' => $this->name]);
    }
}; ?>

<div>
    <!-- Minimal implementation, no UI needed for test -->
</div>
