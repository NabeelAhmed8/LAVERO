<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = Auth::user();
        $recentOrder = session()->get('last_order');

        if (! $recentOrder && $user) {
            $dbOrder = $user->orders()->with('items')->latest()->first();
            if ($dbOrder) {
                $recentOrder = [
                    'order_number' => $dbOrder->order_number,
                    'customer_name' => $dbOrder->customer_name,
                    'email' => $dbOrder->email,
                    'payment_method' => $dbOrder->payment_method,
                    'fulfillment_status' => $dbOrder->fulfillment_status,
                    'tracking_number' => $dbOrder->tracking_number,
                    'items' => $dbOrder->items->map(fn ($item): array => [
                        'name' => $item->product_name,
                        'price' => (float) $item->price,
                        'size' => $item->size,
                        'quantity' => $item->quantity,
                        'image' => $item->image,
                    ])->all(),
                    'subtotal' => (float) $dbOrder->subtotal,
                    'date' => $dbOrder->created_at->format('F j, Y'),
                ];
            }
        }

        $totalOrdersCount = $user ? $user->orders()->count() : 0;
        $activeOrdersCount = $user ? $user->orders()->whereIn('fulfillment_status', ['processing', 'shipped'])->count() : 0;
        $totalLifetimeSpent = $user ? (float) $user->orders()->sum('total') : 0.0;

        $orders = $user
            ? $user->orders()->with('items')->latest()->paginate(5)->withQueryString()
            : new LengthAwarePaginator([], 0, 5);

        return view('profile', [
            'user' => $user,
            'recentOrder' => $recentOrder,
            'orders' => $orders,
            'totalOrdersCount' => $totalOrdersCount,
            'activeOrdersCount' => $activeOrdersCount,
            'totalLifetimeSpent' => $totalLifetimeSpent,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Your client profile details have been updated.');
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Your default delivery destination has been saved.');
    }

    public function updatePayment(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'card_number' => 'required|string|min:12',
            'card_brand' => 'nullable|string|max:50',
        ]);

        $cleaned = preg_replace('/\D/', '', $validated['card_number']);
        $lastFour = substr($cleaned, -4);
        $brand = $validated['card_brand'] ?: 'Visa Signature';

        $user->update([
            'card_last_four' => $lastFour,
            'card_brand' => $brand,
        ]);

        return redirect()->back()->with('success', "Payment card ending in {$lastFour} saved securely to your dossier.");
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided current password does not match our records.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Your password has been successfully updated.');
    }
}
