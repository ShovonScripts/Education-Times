<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $query = Contact::latest();

        if ($request->filter === 'unread') {
            $query->unread();
        } elseif ($request->filter === 'replied') {
            $query->replied();
        } elseif ($request->filter === 'unreplied') {
            $query->unreplied();
        }

        $contacts = $query->paginate(20);
        $unreadCount = Contact::unread()->count();
        $unrepliedCount = Contact::unreplied()->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount', 'unrepliedCount'));
    }

    public function show(Contact $contact): View
    {
        if (!$contact->read_at) {
            $contact->update(['read_at' => now()]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:10000',
        ]);

        $contact->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'রিপ্লাই পাঠানো হয়েছে!');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();
        return back()->with('success', 'Messages ডিলিট করা হয়েছে।');
    }
}
