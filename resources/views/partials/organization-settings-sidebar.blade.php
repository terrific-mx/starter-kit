<div class="lg:w-64 lg:shrink-0 mb-8 lg:mb-0">
    <flux:navlist>
        <flux:navlist.item
            icon="cog-6-tooth"
            :href="route('organizations.settings.general', $organization)"
            :current="request()->routeIs('organizations.settings.general')"
            wire:navigate
        >
            {{ __('General') }}
        </flux:navlist.item>
        <flux:navlist.item
            icon="layout-grid"
            :href="route('organizations.settings.members', $organization)"
            :current="request()->routeIs('organizations.settings.members')"
            wire:navigate
        >
            {{ __('Members') }}
        </flux:navlist.item>
    </flux:navlist>
</div>
