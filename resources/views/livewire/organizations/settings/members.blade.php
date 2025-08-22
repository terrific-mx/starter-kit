<?php

use Livewire\Volt\Component;
use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationInvitation;
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

    public function removeMember(User $member): void
    {
        $this->authorize('removeMember', $this->organization);

        if (! $this->organization->members->contains($member)) {
            abort(403);
        }

        $this->organization->members()->detach($member->id);
    }

    public function revokeInvitation(OrganizationInvitation $invitation): void
    {
        $this->authorize('revoke', $invitation);

        $invitation->delete();

        Flux::toast(
            heading: __('Invitation revoked'),
            text: __('The invitation for :email was revoked.', ['email' => $invitation->email]),
            variant: 'success'
        );

        $this->invitations = $this->getInvitations();
    }
}; ?>

<div>
    @include('partials.organization-settings-breadcrumbs', ['organization' => $organization, 'current' => __('Members')])
    @include('partials.organization-settings-heading')
    <div class="flex flex-col lg:flex-row gap-8">
        @include('partials.organization-settings-sidebar', ['organization' => $organization])
        <div class="flex-1">
            <section class="max-w-lg">
                <header>
                    <flux:heading size="lg">{{ __('Organization Members') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('These are all the members in your organization.') }}</flux:text>
                </header>
                <flux:table class="mt-4">
                    <flux:table.columns>
                        <flux:table.column>{{ __('Member') }}</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($organization->members as $member)
                            <flux:table.row>
                                <flux:table.cell>
                                    <div class="flex items-center gap-2 sm:gap-4">
                                        <flux:avatar :name="$member->name" class="max-sm:size-8" circle />
                                        <div class="flex flex-col">
                                            <flux:heading>{{ $member->name }}
                                                @if (auth()->id() === $member->id)
                                                    <flux:badge size="sm" color="blue" class="ml-1 max-sm:hidden">{{ __('You') }}</flux:badge>
                                                @endif
                                            </flux:heading>
                                            <flux:text class="max-sm:hidden">{{ $member->email }}</flux:text>
                                        </div>
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </section>
            <section class="mt-8">
                <header>
                    <flux:heading size="lg">{{ __('Invite Member') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('Invite a new member to your organization by entering their email address below.') }}</flux:text>
                </header>
                <form wire:submit="sendInvitation" class="max-w-lg space-y-4 mt-4">
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
            <section class="max-w-lg mt-8">
                <header>
                    <flux:heading size="lg">{{ __('Pending Invitations') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('These are the invitations you have sent to join your organization. You can manage them here.') }}</flux:text>
                </header>
                <div class="mt-4">
                    @if ($invitations->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8">
                            <flux:text>
                                <flux:icon name="user-plus" variant="mini" class="mb-4" />
                            </flux:text>
                            <flux:heading size="md" class="mb-1">{{ __('No pending invitations') }}</flux:heading>
                            <flux:text>{{ __('You haven\'t sent any invitations yet.') }}</flux:text>
                        </div>
                    @else
                        <flux:table>
                            <flux:table.columns>
                                <flux:table.column>{{ __('Invitation') }}</flux:table.column>

                            </flux:table.columns>
                            <flux:table.rows>
                                @foreach ($invitations as $invitation)
                                    <flux:table.row>
                                        <flux:table.cell>{{ $invitation->email }}</flux:table.cell>

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
