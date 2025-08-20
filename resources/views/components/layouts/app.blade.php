<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
    <livewire:organizations.create />
</x-layouts.app.sidebar>
