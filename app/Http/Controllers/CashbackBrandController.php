<?php

namespace App\Http\Controllers;

use App\Models\CashbackBrand;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CashbackBrandController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $cashbackBrands = CashbackBrand::orderBy('sort_order')->get();

        return view('admin.cashback-brands.index', compact('cashbackBrands'));
    }

    public function create()
    {
        return view('admin.cashback-brands.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->contentValidationRules() + [
            'logo' => 'nullable|image|max:2048|required_without:logo_path',
            'logo_path' => 'nullable|string|required_without:logo',
        ]);

        $data = $request->only(['store_name', 'cashback_rate', 'redirect_url']);
        $data['status'] = $request->boolean('status', true);

        if ($path = $this->resolveImagePath($request, 'logo', 'logo_path')) {
            $data['logo'] = $path;
        }

        $last = CashbackBrand::max('sort_order') ?? 0;
        $data['sort_order'] = $last + 1;

        CashbackBrand::create($data);

        Cache::forget('home_page_html');

        return redirect()->route('admin.cashback-brands.index')->with('success', 'Cashback brand created successfully!');
    }

    public function edit(CashbackBrand $cashback_brand)
    {
        return view('admin.cashback-brands.edit', ['cashbackBrand' => $cashback_brand]);
    }

    public function update(Request $request, CashbackBrand $cashback_brand)
    {
        $request->validate($this->contentValidationRules() + [
            'logo' => 'nullable|image|max:2048',
            'logo_path' => 'nullable|string',
        ]);

        $updateData = $request->only(['store_name', 'cashback_rate', 'redirect_url']);
        $updateData['status'] = $request->boolean('status');

        $newPath = $this->resolveImagePath($request, 'logo', 'logo_path');
        if ($newPath) {
            $this->deleteImageFile($cashback_brand->logo);
            $updateData['logo'] = $newPath;
        }

        $cashback_brand->update($updateData);

        Cache::forget('home_page_html');

        return redirect()->route('admin.cashback-brands.index')->with('success', 'Cashback brand updated successfully!');
    }

    public function destroy(CashbackBrand $cashback_brand)
    {
        $this->deleteImageFile($cashback_brand->logo);

        $cashback_brand->delete();

        Cache::forget('home_page_html');

        return back()->with('success', 'Cashback brand deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (! $ids || count($ids) === 0) {
            return back()->with('error', 'No cashback brands selected.');
        }

        $brands = CashbackBrand::whereIn('id', $ids)->get();

        foreach ($brands as $brand) {
            $this->deleteImageFile($brand->logo);
            $brand->delete();
        }

        Cache::forget('home_page_html');

        return back()->with('success', 'Selected cashback brands deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:cashback_brands,id',
        ]);

        foreach ($request->order as $index => $id) {
            CashbackBrand::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        Cache::forget('home_page_html');

        return response()->json(['success' => true, 'message' => 'Cashback brands reordered successfully.']);
    }

    public function updateStatus(Request $request, CashbackBrand $cashback_brand)
    {
        $cashback_brand->update(['status' => $request->status]);

        Cache::forget('home_page_html');

        return response()->json(['success' => true, 'message' => 'Cashback brand status updated successfully.']);
    }

    private function contentValidationRules(): array
    {
        return [
            'store_name' => 'required|string|max:255',
            'cashback_rate' => 'required|string|max:20',
            'redirect_url' => 'nullable|string|max:2048',
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
