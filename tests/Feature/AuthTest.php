<?php

use App\Mail\VerificationCodeMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('it renders login and register pages', function () {
    $this->get('/login')->assertStatus(200);
    $this->get('/register')->assertStatus(200);
});

test('it registers a new user with bcrypt, dispatches verification email, and redirects to verify', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'name' => 'Alexander Vance',
        'email' => 'vance@example.com',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
    ]);

    $response->assertRedirect(route('verify.show'));

    $user = User::where('email', 'vance@example.com')->first();
    expect($user)->not->toBeNull();
    expect(Hash::check('secret1234', $user->password))->toBeTrue();
    expect($user->verification_code)->not->toBeNull();
    expect(strlen($user->verification_code))->toBe(6);

    // Verify verification code email was sent
    Mail::assertSent(VerificationCodeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('it verifies the 6-digit code, marks email verified, attaches jwt cookie, and logs in', function () {
    Mail::fake();

    $user = User::create([
        'name' => 'Alexander Vance',
        'email' => 'vance@example.com',
        'password' => Hash::make('secret1234'),
        'verification_code' => '482910',
    ]);

    $response = $this->post('/verify-email', [
        'email' => 'vance@example.com',
        'code' => '482910',
    ]);

    $response->assertRedirect(route('home'));
    $response->assertCookie('lavero_jwt');

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();
    expect($user->verification_code)->toBeNull();

    // Verify welcome mail was sent after verification
    Mail::assertSent(WelcomeMail::class);
    $this->assertAuthenticatedAs($user);
});

test('it rejects incorrect verification code', function () {
    User::create([
        'name' => 'Alexander Vance',
        'email' => 'vance@example.com',
        'password' => Hash::make('secret1234'),
        'verification_code' => '482910',
    ]);

    $response = $this->post('/verify-email', [
        'email' => 'vance@example.com',
        'code' => '999999',
    ]);

    $response->assertSessionHasErrors('code');
    $this->assertGuest();
});

test('it resends a fresh 6-digit verification code to unverified user', function () {
    Mail::fake();

    $user = User::create([
        'name' => 'Alexander Vance',
        'email' => 'vance.resend@example.com',
        'password' => Hash::make('secret1234'),
        'verification_code' => '111111',
    ]);

    $response = $this->post('/verify-email/resend', [
        'email' => 'vance.resend@example.com',
    ]);

    $response->assertRedirect(route('verify.show', ['email' => 'vance.resend@example.com']));
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->verification_code)->not->toBe('111111');
    expect(strlen($user->verification_code))->toBe(6);

    Mail::assertSent(VerificationCodeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('it logs in a user with valid bcrypt credentials and attaches jwt cookie', function () {
    $user = User::create([
        'name' => 'Elena Rostova',
        'email' => 'elena@example.com',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);

    $response = $this->post('/login', [
        'email' => 'elena@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $response->assertCookie('lavero_jwt');
    $this->assertAuthenticatedAs($user);
});

test('it redirects unverified user to verify email page on login attempt', function () {
    User::create([
        'name' => 'Unverified Client',
        'email' => 'unverified@example.com',
        'password' => Hash::make('password123'),
        'email_verified_at' => null,
    ]);

    $response = $this->post('/login', [
        'email' => 'unverified@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('verify.show'));
    $this->assertGuest();
});

test('it rejects login with wrong password', function () {
    User::create([
        'name' => 'Elena Rostova',
        'email' => 'elena@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'elena@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('it renders legal, terms, and privacy policy pages', function () {
    $this->get('/privacy-policy')->assertStatus(200);
    $this->get('/terms-of-service')->assertStatus(200);
    $this->get('/legal-notice')->assertStatus(200);
});

test('jwt service generates and validates tokens correctly', function () {
    $user = User::create([
        'name' => 'Test Subject',
        'email' => 'jwt@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $jwt = new JwtService('test-secret-key-32-characters-minimum');
    $token = $jwt->generateToken($user);

    expect($token)->toBeString();
    expect(count(explode('.', $token)))->toBe(3);

    $payload = $jwt->verifyToken($token);
    expect($payload)->toBeArray();
    expect($payload['email'])->toBe('jwt@example.com');
    expect($payload['name'])->toBe('Test Subject');

    // Tampered token check
    $tampered = $token.'tampered';
    expect($jwt->verifyToken($tampered))->toBeNull();
});

test('it logs out user and forgets jwt cookie', function () {
    $user = User::create([
        'name' => 'Log Out User',
        'email' => 'logout@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $this->actingAs($user);

    $response = $this->post('/logout');
    $response->assertRedirect(route('home'));
    $this->assertGuest();
});

test('authenticated user can view profile and update details', function () {
    $user = User::create([
        'name' => 'Initial Name',
        'email' => 'initial@example.com',
        'password' => Hash::make('password123'),
    ]);

    // Guest redirected to login
    $this->get('/profile')->assertRedirect(route('login'));

    // Authenticated access
    $this->actingAs($user);
    $this->get('/profile')->assertStatus(200);

    // Update details
    $response = $this->put('/profile', [
        'name' => 'Updated Legal Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertSessionHas('success');
    $user->refresh();
    expect($user->name)->toBe('Updated Legal Name');
    expect($user->email)->toBe('updated@example.com');
});

test('user can update password with valid current password', function () {
    $user = User::create([
        'name' => 'Secure Client',
        'email' => 'secure@example.com',
        'password' => Hash::make('oldpassword123'),
    ]);

    $this->actingAs($user);

    // Wrong current password
    $failResponse = $this->put('/profile/password', [
        'current_password' => 'wrongcurrent',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);
    $failResponse->assertSessionHasErrors('current_password');

    // Valid current password
    $successResponse = $this->put('/profile/password', [
        'current_password' => 'oldpassword123',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);
    $successResponse->assertSessionHas('success');

    $user->refresh();
    expect(Hash::check('newpassword123', $user->password))->toBeTrue();
});

test('user can update shipping address and payment method', function () {
    $user = User::create([
        'name' => 'Lord Sterling',
        'email' => 'sterling@example.com',
        'password' => Hash::make('password123'),
    ]);

    $this->actingAs($user);

    // Save Address
    $addressResponse = $this->put('/profile/address', [
        'address' => '12 Rue de la Paix',
        'city' => 'Paris',
        'country' => 'France',
        'postal_code' => '75002',
    ]);
    $addressResponse->assertSessionHas('success');

    // Save Payment
    $paymentResponse = $this->put('/profile/payment', [
        'card_number' => '4111 2222 3333 9981',
        'card_brand' => 'Visa Signature Infinite',
    ]);
    $paymentResponse->assertSessionHas('success');

    $user->refresh();
    expect($user->address)->toBe('12 Rue de la Paix');
    expect($user->city)->toBe('Paris');
    expect($user->card_last_four)->toBe('9981');
    expect($user->card_brand)->toBe('Visa Signature Infinite');
});
