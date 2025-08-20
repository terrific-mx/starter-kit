<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $name = '';

    public function create()
    {
        if (!Auth::check()) {
            abort(403);
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Auth::user()->organizations()->create([
            'name' => $this->name,
        ]);
    }
}; ?>

<div>
    //
</div>
