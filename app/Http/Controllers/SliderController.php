<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'background_image' => 'nullable|image|max:2048|required_without:background_image_path',
            'background_image_path' => 'nullable|string|required_without:background_image',
        ]);

        $data = [
            'status' => $request->boolean('status', true),
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $this->imageService->uploadAndConvert(
                $request->file('background_image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
        } elseif ($request->filled('background_image_path')) {
            $data['background_image'] = $request->input('background_image_path');
        }

        $last = Slider::max('sort_order') ?? 0;
        $data['sort_order'] = $last + 1;

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'background_image' => 'nullable|image|max:2048',
            'background_image_path' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $request->boolean('status'),
        ];

        if ($request->hasFile('background_image')) {
            if ($slider->background_image && File::exists(public_path($slider->background_image))) {
                File::delete(public_path($slider->background_image));
            }

            $updateData['background_image'] = $this->imageService->uploadAndConvert(
                $request->file('background_image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
        } elseif ($request->filled('background_image_path')) {
            $updateData['background_image'] = $request->input('background_image_path');
        }

        $slider->update($updateData);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully!');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->background_image && File::exists(public_path($slider->background_image))) {
            File::delete(public_path($slider->background_image));
        }

        $slider->delete();

        return back()->with('success', 'Slider deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'No sliders selected.');
        }

        $sliders = Slider::whereIn('id', $ids)->get();

        foreach ($sliders as $slider) {
            if ($slider->background_image && File::exists(public_path($slider->background_image))) {
                File::delete(public_path($slider->background_image));
            }
            $slider->delete();
        }

        return back()->with('success', 'Selected sliders deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:sliders,id',
        ]);

        foreach ($request->order as $index => $id) {
            Slider::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Sliders reordered successfully.']);
    }

    public function updateStatus(Request $request, Slider $slider)
    {
        $slider->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Slider status updated successfully.']);
    }
}
