<?php

use App\Models\NewsletterSubscriber;

test('it subscribes an email to the newsletter via json request', function (): void {
    $response = $this->postJson('/newsletter', [
        'email' => 'concierge.subscriber@private.com',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    expect(NewsletterSubscriber::where('email', 'concierge.subscriber@private.com')->exists())->toBeTrue();
});

test('it deduplicates existing newsletter subscriptions', function (): void {
    NewsletterSubscriber::create(['email' => 'subscriber@private.com']);

    $this->postJson('/newsletter', [
        'email' => 'subscriber@private.com',
    ])->assertStatus(200);

    expect(NewsletterSubscriber::where('email', 'subscriber@private.com')->count())->toBe(1);
});

test('it rejects invalid email for newsletter subscription', function (): void {
    $this->postJson('/newsletter', [
        'email' => 'not-a-valid-email',
    ])->assertStatus(422);
});
