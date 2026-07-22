<?php

namespace App\Http\Controllers;

use App\Models\HomeContentBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeContentBlockController extends Controller
{
    public function index()
    {
        $homeContentBlocks = HomeContentBlock::orderBy('sort_order')->get();

        return view('admin.home-content-blocks.index', compact('homeContentBlocks'));
    }

    public function create()
    {
        return view('admin.home-content-blocks.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        $data = $request->only(['title', 'content']);
        $data['status'] = $request->boolean('status', true);

        $last = HomeContentBlock::max('sort_order') ?? 0;
        $data['sort_order'] = $last + 1;

        HomeContentBlock::create($data);

        Cache::forget('home_page_html');

        return redirect()->route('admin.home-content-blocks.index')->with('success', 'Content block created successfully!');
    }

    public function edit(HomeContentBlock $home_content_block)
    {
        return view('admin.home-content-blocks.edit', ['homeContentBlock' => $home_content_block]);
    }

    public function update(Request $request, HomeContentBlock $home_content_block)
    {
        $request->validate($this->validationRules());

        $updateData = $request->only(['title', 'content']);
        $updateData['status'] = $request->boolean('status');

        $home_content_block->update($updateData);

        Cache::forget('home_page_html');

        return redirect()->route('admin.home-content-blocks.index')->with('success', 'Content block updated successfully!');
    }

    public function destroy(HomeContentBlock $home_content_block)
    {
        $home_content_block->delete();

        Cache::forget('home_page_html');

        return back()->with('success', 'Content block deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (! $ids || count($ids) === 0) {
            return back()->with('error', 'No content blocks selected.');
        }

        HomeContentBlock::whereIn('id', $ids)->delete();

        Cache::forget('home_page_html');

        return back()->with('success', 'Selected content blocks deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:home_content_blocks,id',
        ]);

        foreach ($request->order as $index => $id) {
            HomeContentBlock::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        Cache::forget('home_page_html');

        return response()->json(['success' => true, 'message' => 'Content blocks reordered successfully.']);
    }

    public function updateStatus(Request $request, HomeContentBlock $home_content_block)
    {
        $home_content_block->update(['status' => $request->status]);

        Cache::forget('home_page_html');

        return response()->json(['success' => true, 'message' => 'Content block status updated successfully.']);
    }

    private function validationRules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ];
    }
}
