<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $pages = Page::orderBy('sort_order')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        // Create form ke liye blank page object bhejenge
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_title'       => 'required|string|max:255|unique:pages,page_title',
            'seo_url'          => 'required|string|max:255|unique:pages,seo_url',
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string',
            'meta_description' => 'nullable|string',
            'canonical_url'    => 'nullable|url|max:255',
            'schema'           => 'nullable|string',
            'page_content'     => 'nullable|string',
            'media'            => 'nullable|image|max:2048',
            'banner_image'     => 'nullable|image|max:2048',
        ]);

        $validated['status'] = $request->boolean('status');

        foreach (['media', 'banner_image'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $this->imageService->uploadAndConvert(
                    $request->file($field),
                    'uploads',
                    ['quality' => 100, 'preserve_original' => true]
                );
            } elseif ($request->filled($field . '_path')) {
                $validated[$field] = $request->input($field . '_path');
            }
        }

        $last = Page::max('sort_order') ?? 0;
        $validated['sort_order'] = $last + 1;

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'page_title'       => 'required|string|max:255|unique:pages,page_title,' . $page->id,
            'seo_url'          => 'required|string|max:255|unique:pages,seo_url,' . $page->id,
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string',
            'meta_description' => 'nullable|string',
            'canonical_url'    => 'nullable|url|max:255',
            'schema'           => 'nullable|string',
            'page_content'     => 'nullable|string',
            'media'            => 'nullable|image|max:2048',
            'banner_image'     => 'nullable|image|max:2048',
        ]);

        $validated['status'] = $request->boolean('status');

        foreach (['media', 'banner_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($page->{$field} && File::exists(public_path($page->{$field}))) {
                    File::delete(public_path($page->{$field}));
                }

                $validated[$field] = $this->imageService->uploadAndConvert(
                    $request->file($field),
                    'uploads',
                    ['quality' => 100, 'preserve_original' => true]
                );
            } elseif ($request->filled($field . '_path')) {
                $validated[$field] = $request->input($field . '_path');
            } else {
                $validated[$field] = $page->{$field};
            }
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->media && File::exists(public_path($page->media))) {
            File::delete(public_path($page->media));
        }

        if ($page->banner_image && File::exists(public_path($page->banner_image))) {
            File::delete(public_path($page->banner_image));
        }

        $page->delete();

        return back()->with('success', 'Page deleted successfully.');
    }

public function bulkDelete(Request $request)
{
    $ids = $request->ids;

    if (!$ids || count($ids) === 0) {
        return back()->with('error', 'No pages selected.');
    }

    $pages = Page::whereIn('id', $ids)->get();

    foreach ($pages as $page) {
        if ($page->media && Storage::disk('public')->exists($page->media)) {
            Storage::disk('public')->delete($page->media);
        }
        if ($page->banner_image && Storage::disk('public')->exists($page->banner_image)) {
            Storage::disk('public')->delete($page->banner_image);
        }
        $page->delete();
    }

    return back()->with('success', 'Selected pages deleted successfully.');
}

    public function reorder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:pages,id',
            'order.*.sort_order' => 'required|integer|min:1',
        ]);

        foreach ($request->order as $item) {
            Page::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['status' => 'success']);
    }
}
