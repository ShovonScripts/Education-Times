<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = Staff::orderBy('order')->orderBy('staff_type')->paginate(20);
        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'designation_bn' => 'required|string|max:255',
            'designation_en' => 'nullable|string|max:255',
            'staff_type' => 'required|in:editor,reporter,advisor,columnist,correspondent,management',
            'bio_bn' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'photo' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Staff::create($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member added successfully.');
    }

    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff): RedirectResponse
    {
        $validated = $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'designation_bn' => 'required|string|max:255',
            'designation_en' => 'nullable|string|max:255',
            'staff_type' => 'required|in:editor,reporter,advisor,columnist,correspondent,management',
            'bio_bn' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'photo' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}
