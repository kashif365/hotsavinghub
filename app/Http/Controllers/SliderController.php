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
        $request->validate($this->contentValidationRules() + [
            'background_image' => 'nullable|image|max:2048|required_without:background_image_path',
            'background_image_path' => 'nullable|string|required_without:background_image',
        ]);

        $data = $request->only(['label', 'heading', 'subtitle', 'cta_text', 'cta_url', 'badge_color']);
        $data['status'] = $request->boolean('status', true);

        foreach ($this->imageFieldMap() as $fileField => $pathField) {
            if ($path = $this->resolveImagePath($request, $fileField, $pathField)) {
                $data[$fileField] = $path;
            }
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
        $request->validate($this->contentValidationRules() + [
            'background_image' => 'nullable|image|max:2048',
            'background_image_path' => 'nullable|string',
        ]);

        $updateData = $request->only(['label', 'heading', 'subtitle', 'cta_text', 'cta_url', 'badge_color']);
        $updateData['status'] = $request->boolean('status');

        foreach ($this->imageFieldMap() as $fileField => $pathField) {
            $newPath = $this->resolveImagePath($request, $fileField, $pathField);
            if ($newPath) {
                $this->deleteImageFile($slider->{$fileField});
                $updateData[$fileField] = $newPath;
            }
        }

        $slider->update($updateData);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully!');
    }

    public function destroy(Slider $slider)
    {
        foreach (array_keys($this->imageFieldMap()) as $fileField) {
            $this->deleteImageFile($slider->{$fileField});
        }

        $slider->delete();

        return back()->with('success', 'Slider deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (! $ids || count($ids) === 0) {
            return back()->with('error', 'No sliders selected.');
        }

        $sliders = Slider::whereIn('id', $ids)->get();

        foreach ($sliders as $slider) {
            foreach (array_keys($this->imageFieldMap()) as $fileField) {
                $this->deleteImageFile($slider->{$fileField});
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

    /**
     * Maps each image column to the hidden "*_path" input used by the media library picker.
     */
    private function imageFieldMap(): array
    {
        return [
            'background_image' => 'background_image_path',
            'secondary_image' => 'secondary_image_path',
            'logo' => 'logo_path',
        ];
    }

    private function contentValidationRules(): array
    {
        return [
            'secondary_image' => 'nullable|image|max:2048',
            'secondary_image_path' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'logo_path' => 'nullable|string',
            'label' => 'nullable|string|max:255',
            'heading' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:2048',
            'badge_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    private function resolveImagePath(Request $request, string $fileField, string $pathField): ?string
    {
        if ($request->hasFile($fileField)) {
            return $this->imageService->uploadAndConvert(
                $request->file($fileField),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
        }

        if ($request->filled($pathField)) {
            return $request->input($pathField);
        }

        return null;
    }

    private function deleteImageFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
