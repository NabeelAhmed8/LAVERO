<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->user = User::create([
        'name' => 'Lord Sterling',
        'email' => 'sterling@london.co.uk',
        'password' => Hash::make('old-password-123'),
        'email_verified_at' => now(),
    ]);
});

test('it redirects guests trying to access profile', function (): void {
    $this->get('/profile')->assertRedirect(route('login'));
});

test('it renders profile page for authenticated client', function (): void {
    $this->actingAs($this->user)->get('/profile')->assertStatus(200);
});

test('it updates client personal details', function (): void {
    $response = $this->actingAs($this->user)->put('/profile', [
        'name' => 'Duke Sterling of Mayfair',
        'email' => 'sterling.mayfair@london.co.uk',
    ]);

    $response->assertRedirect();
    $this->user->refresh();
    expect($this->user->name)->toBe('Duke Sterling of Mayfair');
    expect($this->user->email)->toBe('sterling.mayfair@london.co.uk');
});

test('it updates client delivery address', function (): void {
    $response = $this->actingAs($this->user)->put('/profile/address', [
        'address' => '14 Savile Row, Penthouse',
        'city' => 'London',
        'country' => 'United Kingdom',
        'postal_code' => 'W1S 3JN',
    ]);

    $response->assertRedirect();
    $this->user->refresh();
    expect($this->user->address)->toBe('14 Savile Row, Penthouse');
    expect($this->user->postal_code)->toBe('W1S 3JN');
});

test('it updates client payment card information', function (): void {
    $response = $this->actingAs($this->user)->put('/profile/payment', [
        'card_number' => '4532 8920 1123 7890',
        'card_brand' => 'Mastercard World Elite',
    ]);

    $response->assertRedirect();
    $this->user->refresh();
    expect($this->user->card_last_four)->toBe('7890');
    expect($this->user->card_brand)->toBe('Mastercard World Elite');
});

test('it updates client password with valid current password', function (): void {
    $response = $this->actingAs($this->user)->put('/profile/password', [
        'current_password' => 'old-password-123',
        'password' => 'new-secure-password-456',
        'password_confirmation' => 'new-secure-password-456',
    ]);

    $response->assertRedirect();
    $this->user->refresh();
    expect(Hash::check('new-secure-password-456', $this->user->password))->toBeTrue();
});

test('it rejects password update with incorrect current password', function (): void {
    $response = $this->actingAs($this->user)->put('/profile/password', [
        'current_password' => 'wrong-current-password',
        'password' => 'new-secure-password-456',
        'password_confirmation' => 'new-secure-password-456',
    ]);

    $response->assertSessionHasErrors('current_password');
    $this->user->refresh();
    expect(Hash::check('old-password-123', $this->user->password))->toBeTrue();
});
