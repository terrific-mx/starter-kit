<?php

use Livewire\Volt\Component;

new class extends Component {
    public $name;
    public $organization;

    public function mount($organization)
    {
        $this->organization = $organization;
        $this->name = $organization->name;
    }

    public function edit()
    {
        $this->organization->update(['name' => $this->name]);
    }
}; ?>

<div>
    <!-- Minimal implementation, no UI needed for test -->
</div>
