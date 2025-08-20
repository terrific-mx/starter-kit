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

<div>
    //
</div>
