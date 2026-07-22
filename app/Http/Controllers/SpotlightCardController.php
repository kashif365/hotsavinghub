<?php

namespace App\Http\Controllers;

use App\Models\SpotlightCard;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SpotlightCardController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $spotlightCards = SpotlightCard::orderBy('sort_order')->get();

        return view('admin.spotlight-cards.index', compact('spotlightCards'));
    }

    public function create()
    {
        return view('admin.spotlight-cards.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->contentValidationRules() + [
            'image' => 'nullable|image|max:2048|required_without:image_path',
            'image_path' => 'nullable|string|required_without:image',
        ]);

        $data = $request->only(['heading', 'cta_label', 'cta_url', 'bg_color']);
        $data['status'] = $request->boolean('status', true);

        foreach ($this->imageFieldMap() as $fileField => $pathField) {
            if ($path = $this->resolveImagePath($request, $fileField, $pathField)) {
                $data[$fileField] = $path;
            }
        }

        $last = SpotlightCard::max('sort_order') ?? 0;
        $data['sort_order'] = $last + 1;

        SpotlightCard::create($data);

        Cache::forget('home_page_html');

        return redirect()->route('admin.spotlight-cards.index')->with('success', 'Spotlight card created successfully!');
    }

    public function edit(SpotlightCard $spotlight_card)
    {
        return view('admin.spotlight-cards.edit', ['spotlightCard' => $spotlight_card]);
    }

    public function update(Request $request, SpotlightCard $spotlight_card)
    {
        $request->validate($this->contentValidationRules() + [
            'image' => 'nullable|image|max:2048',
            'image_path' => 'nullable|string',
        ]);

        $updateData = $request->only(['heading', 'cta_label', 'cta_url', 'bg_color']);
        $updateData['status'] = $request->boolean('status');

        foreach ($this->imageFieldMap() as $fileField => $pathField) {
            $newPath = $this->resolveImagePath($request, $fileField, $pathField);
            if ($newPath) {
                $this->deleteImageFile($spotlight_card->{$fileField});
                $updateData[$fileField] = $newPath;
            }
        }

        $spotlight_card->update($updateData);

        Cache::forget('home_page_html');

        return redirect()->route('admin.spotlight-cards.index')->with('success', 'Spotlight card updated successfully!');
    }

    public function destroy(SpotlightCard $spotlight_card)
    {
        foreach (array_keys($this->imageFieldMap()) as $fileField) {
            $this->deleteImageFile($spotlight_card->{$fileField});
        }

        $spotlight_card->delete();

        Cache::forget('home_page_html');

        return back()->with('success', 'Spotlight card deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (! $ids || count($ids) === 0) {
            return back()->with('error', 'No spotlight cards selected.');
        }

        $cards = SpotlightCard::whereIn('id', $ids)->get();

        foreach ($cards as $card) {
            foreach (array_keys($this->imageFieldMap()) as $fileField) {
                $this->deleteImageFile($card->{$fileField});
            }
            $card->delete();
        }

        Cache::forget('home_page_html');

        return back()->with('success', 'Selected spotlight cards deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:spotlight_cards,id',
        ]);

        foreach ($request->order as $index => $id) {
            SpotlightCard::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        Cache::forget('home_page_html');

        return response()->json(['success' => true, 'message' => 'Spotlight cards reordered successfully.']);
    }

    public function updateStatus(Request $request, SpotlightCard $spotlight_card)
    {
        $spotlight_card->update(['status' => $request->status]);

        Cache::forget('home_page_html');

        return response()->json(['success' => true, 'message' => 'Spotlight card status updated successfully.']);
    }

    /**
     * Maps each image column to the hidden "*_path" input used by the media library picker.
     */
    private function imageFieldMap(): array
    {
        return [
            'image' => 'image_path',
            'logo' => 'logo_path',
        ];
    }

    private function contentValidationRules(): array
    {
        return [
            'logo' => 'nullable|image|max:2048',
            'logo_path' => 'nullable|string',
            'heading' => 'nullable|string|max:255',
            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:2048',
            'bg_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
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
