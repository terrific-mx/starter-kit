<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('redirects unsubscribed user to dashboard', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = actingAs($user)->get('/billing-portal');

    $response->assertRedirect(route('subscription-required'));
});
