<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false)->orWhereNotNull('unsubscribed_at');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->latest()->paginate(20);
        $totalActive = NewsletterSubscriber::active()->count();
        $totalInactive = NewsletterSubscriber::where('is_active', false)
            ->orWhereNotNull('unsubscribed_at')->count();

        return view('admin.newsletter.index', compact('subscribers', 'totalActive', 'totalInactive'));
    }

    public function toggleActive(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $newState = !$subscriber->is_active;
        $subscriber->update([
            'is_active' => $newState,
            'unsubscribed_at' => $newState ? null : now(),
        ]);

        return back()->with('success', $newState ? 'Subscriber activated.' : 'Subscriber deactivated.');
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber deleted successfully.');
    }
}
