<?php

use App\Models\ContactMessage;

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('salon contact requests are persisted to database', function () {
    $response = $this->post('/contact', [
        'name' => 'Baroness Charlotte',
        'email' => 'charlotte@paris.fr',
        'salon' => 'Paris',
        'message' => 'Requesting private fitting for Autumn/Winter evening gown.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect(ContactMessage::where('email', 'charlotte@paris.fr')->exists())->toBeTrue();
});
