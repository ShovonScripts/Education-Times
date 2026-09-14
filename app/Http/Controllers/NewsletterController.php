<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $message = $this->storeSubscriber($validated['email']);

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('newsletter_success', $message);
    }

    private function storeSubscriber(string $email): string
    {
        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing) {
            if ($existing->is_active && !$existing->unsubscribed_at) {
                return 'আপনি ইতিমধ্যে সাবস্ক্রাইব করেছেন।';
            }
            $existing->update(['is_active' => true, 'unsubscribed_at' => null]);
            return 'আপনার সাবস্ক্রিপশন পুনরুদ্ধার করা হয়েছে।';
        }

        NewsletterSubscriber::create([
            'email' => $email,
            'channel' => 'email',
            'is_active' => true,
        ]);

        return 'সাবস্ক্রিপশন সফল! ধন্যবাদ।';
    }
}
