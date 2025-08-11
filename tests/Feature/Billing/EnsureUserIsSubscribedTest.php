<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('redirects unsubscribed users to the subscription-required page when accessing a protected route', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    expect($user->subscribed())->toBeFalse();

    actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('subscription-required'));
});
