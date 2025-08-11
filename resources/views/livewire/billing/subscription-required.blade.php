<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Actions\Logout;

new #[Layout('components.layouts.auth')] class extends Component {
    public function mount()
    {
        if (Auth::user()?->subscribed('default')) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function goToCheckout()
    {
        $stripePriceId = config('services.stripe.price_id');

        $this->redirect(Auth::user()->newSubscription('default', $stripePriceId)
            ->trialDays(31)
            ->checkout([
                'success_url' => route('settings.profile'),
                'cancel_url' => route('subscription-required'),
            ])->asStripeCheckoutSession()->url, navigate: false);
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="mt-4 flex flex-col gap-6">
    <flux:text class="text-center">
        {{ __('You need to subscribe to our service to continue.') }}
    </flux:text>
    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="goToCheckout" variant="primary" class="w-full">
            {{ __('Proceed to Checkout') }}
        </flux:button>
        <flux:link class="text-sm cursor-pointer" wire:click="logout">
            {{ __('Log out') }}
        </flux:link>
    </div>
</div>
