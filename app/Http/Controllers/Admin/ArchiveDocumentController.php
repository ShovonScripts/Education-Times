<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchiveDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArchiveDocumentController extends Controller
{
    public function index(): View
    {
        $documents = ArchiveDocument::latest()->paginate(20);
        return view('admin.archive.index', compact('documents'));
    }

    public function create(): View
    {
        $years = range(date('Y'), 2000);
        return view('admin.archive.create', compact('years'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_bn' => 'nullable|string',
            'file_path' => 'required|string|max:500',
            'file_type' => 'nullable|string|max:50',
            'file_size' => 'nullable|integer',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'subcategory' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($validated['title_bn']);
        $validated['slug'] = $slug ?: 'doc-' . Str::random(8);
        $validated['uploaded_by'] = auth()->id();

        ArchiveDocument::create($validated);

        return redirect()->route('admin.archive.index')
            ->with('success', 'Archives যোগ করা হয়েছে।');
    }

    public function edit(ArchiveDocument $archiveDocument): View
    {
        $years = range(date('Y'), 2000);
        return view('admin.archive.edit', compact('archiveDocument', 'years'));
    }

    public function update(Request $request, ArchiveDocument $archiveDocument): RedirectResponse
    {
        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_bn' => 'nullable|string',
            'file_path' => 'required|string|max:500',
            'file_type' => 'nullable|string|max:50',
            'file_size' => 'nullable|integer',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'subcategory' => 'nullable|string|max:255',
        ]);

        $archiveDocument->update($validated);

        return redirect()->route('admin.archive.index')
            ->with('success', 'Archives আপডেট করা হয়েছে।');
    }

    public function destroy(ArchiveDocument $archiveDocument): RedirectResponse
    {
        $archiveDocument->delete();
        return redirect()->route('admin.archive.index')
            ->with('success', 'Archives ডিলিট করা হয়েছে।');
    }
}
