<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(public JwtService $jwtService) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our atelier records.',
            ])->onlyInput('email');
        }

        if (! $user->email_verified_at) {
            session()->put('verify_email', $user->email);

            return redirect()->route('verify.show')->withErrors([
                'code' => 'Please verify your email address before signing in. A verification code was sent to your email.',
            ]);
        }

        // Generate JWT token
        $jwtToken = $this->jwtService->generateToken($user);

        // Standard Laravel session login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Attach JWT cookie (1 day = 1440 minutes, httpOnly)
        Cookie::queue('lavero_jwt', $jwtToken, 1440, '/', null, false, true);

        return redirect()->intended(route('home'))->with('success', "Welcome back to Maison LAVÉRO, {$user->name}.");
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $code = (string) random_int(100000, 999999);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'verification_code' => $code,
        ]);

        // Dispatch 6-digit verification code email
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $code));
        } catch (\Throwable $e) {
            Log::warning('Verification email could not be sent: '.$e->getMessage());
        }

        session()->put('verify_email', $user->email);

        return redirect()->route('verify.show')->with('success', 'A 6-digit verification code has been dispatched to your email.');
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        $email = $request->input('email', session()->get('verify_email'));

        if (! $email) {
            return redirect()->route('register');
        }

        return view('auth.verify', ['email' => $email]);
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || $user->verification_code !== $validated['code']) {
            return back()->withErrors([
                'code' => 'Invalid or expired verification code. Please check your email.',
            ])->withInput();
        }

        // Mark verified & clear code
        $user->verification_code = null;
        $user->email_verified_at = now();
        $user->save();

        // Send welcome invitation email
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Throwable $e) {
            Log::warning('Welcome email could not be sent: '.$e->getMessage());
        }

        // Authenticate & attach JWT cookie
        $jwtToken = $this->jwtService->generateToken($user);
        Auth::login($user);
        $request->session()->regenerate();
        session()->forget('verify_email');
        Cookie::queue('lavero_jwt', $jwtToken, 1440, '/', null, false, true);

        return redirect()->route('home')->with('success', "Email verified successfully! Welcome to Maison LAVÉRO, {$user->name}.");
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $email = $request->input('email', session()->get('verify_email'));

        if (! $email) {
            return redirect()->route('register');
        }

        $user = User::where('email', $email)->first();

        if ($user && ! $user->email_verified_at) {
            $code = (string) random_int(100000, 999999);
            $user->update(['verification_code' => $code]);

            try {
                Mail::to($user->email)->send(new VerificationCodeMail($user, $code));
            } catch (\Throwable $e) {
                Log::warning('Resend verification email failed: '.$e->getMessage());
            }
        }

        session()->put('verify_email', $email);

        return redirect()->route('verify.show', ['email' => $email])->with('success', 'A fresh verification code has been dispatched to your email.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('lavero_jwt'));

        return redirect()->route('home')->with('success', 'You have been safely signed out.');
    }
}
