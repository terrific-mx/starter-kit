<div>
    @include('partials.organization-settings-breadcrumbs', ['organization' => $organization, 'current' => __('Members')])
    @include('partials.organization-settings-heading')
    <div class="flex flex-col lg:flex-row gap-8">
        @include('partials.organization-settings-sidebar', ['organization' => $organization])
        <div class="flex-1">
            <section class="max-w-lg space-y-4">
                <header>
                    <flux:heading size="lg">{{ __('Organization Members') }}</flux:heading>
                    <flux:text>{{ __('These are all the members in your organization.') }}</flux:text>
                </header>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Name') }}</flux:table.column>
                        <flux:table.column>{{ __('Email address') }}</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($organization->members as $member)
                            <flux:table.row>
                                <flux:table.cell>{{ $member->name }}</flux:table.cell>
                                <flux:table.cell>{{ $member->email }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </section>
            <section class="mt-8 space-y-4 max-w-lg">
                <header>
                    <flux:heading size="lg">{{ __('Invite Member') }}</flux:heading>
                    <flux:text>{{ __('Invite a new member to your organization by entering their email address below.') }}</flux:text>
                </header>
                <form wire:submit="sendInvitation" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Email address') }}</flux:label>
                        <flux:input.group>
                            <flux:input
                                type="email"
                                wire:model="email"
                                placeholder="{{ __('Enter member email') }}"
                                icon="user"
                            />
                            <flux:button type="submit">
                                {{ __('Send Invitation') }}
                            </flux:button>
                        </flux:input.group>
                        <flux:error name="email" />
                    </flux:field>
                </form>
            </section>
            <section class="mt-8 space-y-4 max-w-lg">
                <header>
                    <flux:heading size="lg">{{ __('Pending Invitations') }}</flux:heading>
                    <flux:text>{{ __('These are the invitations you have sent to join your organization. You can manage them here.') }}</flux:text>
                </header>
                <div>
                    @if ($invitations->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8 space-y-4">
                            <flux:text>
                                <flux:icon name="user-plus" variant="mini" />
                            </flux:text>
                            <flux:heading size="md">{{ __('No pending invitations') }}</flux:heading>
                            <flux:text>{{ __('You haven\'t sent any invitations yet.') }}</flux:text>
                        </div>
                    @else
                        <flux:table>
                            <flux:table.columns>
                                <flux:table.column>{{ __('Email address') }}</flux:table.column>
                                <flux:table.column>{{ __('Status') }}</flux:table.column>
                            </flux:table.columns>
                            <flux:table.rows>
                                @foreach ($invitations as $invitation)
                                    <flux:table.row>
                                        <flux:table.cell>{{ $invitation->email }}</flux:table.cell>
                                        <flux:table.cell>
                                            <flux:badge color="zinc" size="sm">{{ __('Pending') }}</flux:badge>
                                        </flux:table.cell>
                                        <flux:table.cell align="end">
                                            <flux:button color="danger" variant="subtle" size="sm" wire:click="revokeInvitation({{ $invitation->id }})">
                                                {{ __('Revoke') }}
                                            </flux:button>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            </flux:table.rows>
                        </flux:table>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
