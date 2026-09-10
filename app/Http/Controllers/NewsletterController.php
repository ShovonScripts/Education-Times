<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
        ]);

        $existing = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->is_active && !$existing->unsubscribed_at) {
                return response()->json(['message' => 'আপনি ইতিমধ্যে সাবস্ক্রাইব করেছেন।']);
            }
            $existing->update(['is_active' => true, 'unsubscribed_at' => null]);
            return response()->json(['message' => 'আপনার সাবস্ক্রিপশন পুনরুদ্ধার করা হয়েছে।']);
        }

        NewsletterSubscriber::create([
            'email' => $validated['email'],
            'channel' => 'email',
            'is_active' => true,
        ]);

        return response()->json(['message' => 'সাবস্ক্রিপশন সফল! ধন্যবাদ।']);
    }
}
