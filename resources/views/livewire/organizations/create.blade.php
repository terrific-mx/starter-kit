<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';

    public function create()
    {
        if (!auth()->check()) {
            abort(403);
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        auth()->user()->organizations()->create([
            'name' => $this->name,
        ]);
    }
}; ?>

<div>
    //
</div>
