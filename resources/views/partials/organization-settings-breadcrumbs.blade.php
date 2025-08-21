<flux:breadcrumbs class="mb-6">
    <flux:breadcrumbs.item href="{{ route('home') }}">{{ __('Home') }}</flux:breadcrumbs.item>
    <flux:breadcrumbs.item href="{{ route('organizations.show', $organization) }}">{{ $organization->name }}</flux:breadcrumbs.item>
    <flux:breadcrumbs.item href="{{ route('organizations.settings.general', $organization) }}">{{ __('Settings') }}</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>{{ $current }}</flux:breadcrumbs.item>
</flux:breadcrumbs>
