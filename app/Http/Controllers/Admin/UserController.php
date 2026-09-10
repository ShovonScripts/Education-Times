<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('district');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            });
        }
        if ($request->filled('role')) {
            match ($request->role) {
                'admin' => $query->where('is_admin', true),
                'editor' => $query->where('is_editor', true)->where('is_admin', false),
                'user' => $query->where('is_admin', false)->where('is_editor', false),
                default => null,
            };
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(30)->withQueryString();
        $totalUsers = User::count();
        $adminCount = User::where('is_admin', true)->count();
        $editorCount = User::where('is_editor', true)->where('is_admin', false)->count();
        $activeCount = User::where('is_active', true)->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'adminCount', 'editorCount', 'activeCount'));
    }

    public function edit(User $user): View
    {
        $districts = District::orderBy('name_bn')->get();
        return view('admin.users.edit', compact('user', 'districts'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'district_id' => 'nullable|exists:districts,id',
            'designation' => 'nullable|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
            'is_editor' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $validated['is_admin'] = $request->boolean('is_admin');
        $validated['is_editor'] = $request->boolean('is_editor');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // Prevent self-demotion from admin
        if ($user->id === auth()->id() && !$validated['is_admin']) {
            return back()->with('error', 'আপনি নিজেকে Admins থেকে সরাতে পারবেন No!');
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Users আপডেট হয়েছে!');
    }

    public function create(): View
    {
        $districts = District::orderBy('name_bn')->get();
        return view('admin.users.create', compact('districts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'district_id' => 'nullable|exists:districts,id',
            'designation' => 'nullable|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
            'is_editor' => 'nullable|boolean',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['is_admin'] = $request->boolean('is_admin');
        $validated['is_editor'] = $request->boolean('is_editor');
        $validated['is_active'] = true;
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'নতুন Users তৈরি হয়েছে!');
    }

    public function toggleRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => 'required|in:admin,editor,user']);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজের রোল পরিবর্তন করতে পারবেন No!');
        }

        $user->update([
            'is_admin' => $request->role === 'admin',
            'is_editor' => in_array($request->role, ['admin', 'editor']),
        ]);

        $roleLabels = ['admin' => 'Admins', 'editor' => 'এডিটর', 'user' => 'Users'];
        return back()->with('success', "{$user->name} কে {$roleLabels[$request->role]} করা হয়েছে!");
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজেকে Inactive করতে পারবেন No!');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'Active' : 'Inactive';
        return back()->with('success', "{$user->name} কে {$status} করা হয়েছে!");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজেকে ডিলিট করতে পারবেন No!');
        }

        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'শেষ Adminsকে ডিলিট করা যাবে No!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Users ডিলিট করা হয়েছে!');
    }
}
