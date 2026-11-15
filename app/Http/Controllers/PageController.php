<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function lookbook(): View
    {
        $featuredProducts = Product::where('featured', true)->take(6)->get();

        return view('lookbook', [
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'salon' => 'required|string|in:Paris,Milano,Digital',
            'message' => 'nullable|string|max:1000',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Your private salon appointment request has been received. Our concierge will contact you within 24 hours.');
    }

    public function subscribeNewsletter(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:150',
        ]);

        NewsletterSubscriber::firstOrCreate([
            'email' => strtolower(trim($validated['email'])),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your private salon invitation has been confirmed.',
            ]);
        }

        return redirect()->back()->with('success', 'Your private salon invitation has been confirmed.');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function legal(): View
    {
        return view('pages.legal');
    }
}
