<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;
use App\Models\Organization;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user has an active subscription.
     */
    public function withSubscription(array $overrides = []): static
    {
        return $this->afterCreating(function ($user) use ($overrides) {
            $subscription = Subscription::factory()
                ->for($user)
                ->state($overrides)
                ->create();

            SubscriptionItem::factory()
                ->for($subscription)
                ->state([
                    'stripe_price' => config('services.stripe.price_id'),
                    'quantity' => 1,
                ])
                ->create();
        });
    }

    /**
     * Indicate that the user has a personal organization and sets it as current.
     */
    public function withPersonalOrganization(array $overrides = []): static
    {
        return $this->afterCreating(function ($user) use ($overrides) {
            Organization::factory()
                ->state(array_merge([
                    'user_id' => $user->id,
                    'personal' => true,
                    'name' => $user->name,
                ], $overrides))
                ->create();
        });
    }
}
