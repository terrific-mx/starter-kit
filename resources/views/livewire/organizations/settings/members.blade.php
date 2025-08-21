<?php

use Livewire\Volt\Component;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationInvitation as OrganizationInvitationNotification;
use Flux\Flux;
use Illuminate\Validation\Rule;

new class extends Component {
    public Organization $organization;
    public Collection $invitations;

    public string $email = '';

    public function mount()
    {
        $this->invitations = $this->getInvitations();
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('organization_invitations', 'email')->where(
                    fn ($query) => $query->where('organization_id', $this->organization->id)
                ),
            ],
        ];
    }

    public function sendInvitation()
    {
        $this->authorize('invite', $this->organization);

        $this->validate();

        $invitation = $this->organization->inviteMember($this->email);

        Notification::route('mail', $this->email)
            ->notify(new OrganizationInvitationNotification($invitation));

        Flux::toast(
            heading: __('Invitation sent'),
            text: __('The invitation was sent to :email.', ['email' => $this->email]),
            variant: 'success'
        );

        $this->reset('email');

        $this->invitations = $this->getInvitations();
    }

    private function getInvitations(): Collection
    {
        return $this->organization->invitations()->latest()->get();
    }
}; ?>

<div>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('home') }}">{{ __('Home') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organizations.show', $organization) }}">{{ $organization->name }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('organizations.settings.general', $organization) }}">{{ __('Settings') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ __('Members') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <div class="mb-8">
        <flux:heading size="lg">
            {{ __('Organization Settings') }}
        </flux:heading>
        <flux:text class="mt-1">
            {{ __('Manage all aspects of your organization, including general details and members.') }}
        </flux:text>
    </div>
    <div class="flex flex-col lg:flex-row gap-8">
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
        <div class="flex-1">
            <div class="space-y-6">
                <div class="mb-4">
                    <flux:heading size="lg">{{ __('Invite Member') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('Invite a new member to your organization by entering their email address below.') }}</flux:text>
                </div>
                <form wire:submit="sendInvitation" class="max-w-lg space-y-4">
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
            </div>
            <div class="max-w-lg mt-8">
                <div class="mb-4">
                    <flux:heading size="lg">{{ __('Pending Invitations') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('These are the invitations you have sent to join your organization. You can manage them here.') }}</flux:text>
                </div>
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
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>
    </div>
