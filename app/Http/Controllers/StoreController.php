<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Category;
use App\Models\Events;
use App\Models\Networks;
use App\Services\ActivityLogService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $stores = Store::with(['categories', 'events', 'currentNetwork', 'availableNetwork'])
                       ->orderBy('sort_order')
                       ->get();

        // Log the view action
        ActivityLogService::log('view', 'Viewed stores list', null, null, null, request());

        return view('admin.stores.index', compact('stores'));
    }

public function create()
{
    $categories = Category::pluck('category_name', 'id');
    $events     = Events::pluck('event_name', 'id');
    $networks   = Networks::pluck('name', 'id');

    $store = new Store();
    // create form ke liye empty relations taake Blade me ->categories / ->events safe rahen
    $store->setRelation('categories', collect([]));
    $store->setRelation('events', collect([]));

    return view('admin.stores.create', compact('categories', 'events', 'networks', 'store'));
}
    public function store(Request $request)
    {
    $validated = $request->validate([
            'store_name'        => 'required|string|max:255',
            'seo_url'           => 'required|string|unique:stores,seo_url',
            'facebook_url'      => 'nullable|url',
            'twitter_url'       => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'youtube_url'       => 'nullable|url',
            'current_network'   => 'nullable|exists:networks,id',
            'available_network' => 'nullable|exists:networks,id',
            'categories'        => 'nullable|array',
            'categories.*'      => 'exists:categories,id',
            'events'            => 'nullable|array',
            'events.*'          => 'exists:events,id',
            'faqs'              => 'nullable|string',
            'meta_keywords'     => 'nullable|string|max:255',
            'canonical_url'     => 'nullable|url|max:255',
            'schema'            => 'nullable|string',
        ]);

    // Log incoming faqs payload for debugging — will appear in storage/logs/laravel.log
    Log::info('StoreController@store incoming faqs', ['faqs' => $request->input('faqs')]);

    // fix: exclude the actual input names (categories, events)
    $data = $request->except(['categories', 'events']);
    
    // Handle checkbox fields using Laravel's boolean() method
    $data['covid_disable'] = $request->boolean('covid_disable');
    $data['featured'] = $request->boolean('featured');
    $data['recommended'] = $request->boolean('recommended');
    $data['auto_sort'] = $request->boolean('auto_sort');
    $data['show_trending'] = $request->boolean('show_trending');
    $data['status'] = $request->boolean('status');
    $data['student_discount'] = $request->boolean('student_discount');

    // Ensure seo_url is a slug (server-side safeguard)
    if (empty($data['seo_url']) && !empty($request->input('store_name'))) {
        $data['seo_url'] = Str::slug($request->input('store_name'));
    } elseif (!empty($data['seo_url'])) {
        $data['seo_url'] = Str::slug($data['seo_url']);
    }

        // Ensure seo_url is a slug (server-side safeguard)
        if (empty($data['seo_url']) && !empty($request->input('store_name'))) {
            $data['seo_url'] = Str::slug($request->input('store_name'));
        } elseif (!empty($data['seo_url'])) {
            $data['seo_url'] = Str::slug($data['seo_url']);
        }

        // Store Logo Upload - Use ImageService for auto WebP conversion
        if ($request->hasFile('store_logo')) {
            $path = $this->imageService->uploadAndConvert(
                $request->file('store_logo'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
            $data['store_logo'] = $path;
        } elseif ($request->has('store_logo_path')) {
            // If image selected from media library
            $data['store_logo'] = $request->input('store_logo_path');
        }

        // Cover Image Upload - Use ImageService for auto WebP conversion
        if ($request->hasFile('cover_image')) {
            $path = $this->imageService->uploadAndConvert(
                $request->file('cover_image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
            $data['cover_image'] = $path;
        } elseif ($request->has('cover_image_path')) {
            // If image selected from media library
            $data['cover_image'] = $request->input('cover_image_path');
        }

        // sort_order set karna (last + 1)
        $last = Store::max('sort_order') ?? 0;
        $data['sort_order'] = $last + 1;

        $store = Store::create($data);

        // ✅ Attach categories & events to pivot
        $store->categories()->sync($request->categories ?? []);
        $store->events()->sync($request->events ?? []);

        Cache::forget('nav_category_menu_v1');

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully!');
    }


public function edit(Store $store)
{
    $categories = Category::pluck('category_name', 'id');
    $events     = Events::pluck('event_name', 'id');
    $networks   = Networks::pluck('name', 'id');

    $store->load(['categories','events']); // edit pe pre-load

    return view('admin.stores.edit', compact('store', 'categories', 'events', 'networks'));
}

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'store_name'        => 'required|string|max:255',
            'seo_url'           => 'required|string|unique:stores,seo_url,' . $store->id,
            'facebook_url'      => 'nullable|url',
            'twitter_url'       => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'youtube_url'       => 'nullable|url',
            'current_network'   => 'nullable|exists:networks,id',
            'available_network' => 'nullable|exists:networks,id',
            'categories'        => 'nullable|array',
            'categories.*'      => 'exists:categories,id',
            'events'            => 'nullable|array',
            'events.*'          => 'exists:events,id',
            'faqs'              => 'nullable|string',
            'meta_keywords'     => 'nullable|string|max:255',
            'canonical_url'     => 'nullable|url|max:255',
            'schema'            => 'nullable|string',
        ]);

    $data = $request->except(['categories', 'events', 'store_logo', 'store_logo_path', 'cover_image', 'cover_image_path']);
    
    // Handle checkbox fields using Laravel's boolean() method
    $data['covid_disable'] = $request->boolean('covid_disable');
    $data['featured'] = $request->boolean('featured');
    $data['recommended'] = $request->boolean('recommended');
    $data['auto_sort'] = $request->boolean('auto_sort');
    $data['show_trending'] = $request->boolean('show_trending');
    $data['status'] = $request->boolean('status');
    $data['student_discount'] = $request->boolean('student_discount');

        // Store Logo Update
        // Store Logo Update - Use ImageService for auto WebP conversion
        if ($request->hasFile('store_logo')) {
            if ($store->store_logo && File::exists(public_path($store->store_logo))) {
                File::delete(public_path($store->store_logo));
            }
            $path = $this->imageService->uploadAndConvert(
                $request->file('store_logo'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
            $data['store_logo'] = $path;
        } elseif ($request->filled('store_logo_path')) {
            // If image selected from media library (check if not empty)
            $data['store_logo'] = $request->input('store_logo_path');
        } else {
            // Preserve old image if no new upload
            unset($data['store_logo']); // Remove from data array to preserve existing
        }

        // Cover Image Update - Use ImageService for auto WebP conversion
        if ($request->hasFile('cover_image')) {
            if ($store->cover_image && File::exists(public_path($store->cover_image))) {
                File::delete(public_path($store->cover_image));
            }
            $path = $this->imageService->uploadAndConvert(
                $request->file('cover_image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
            $data['cover_image'] = $path;
        } elseif ($request->filled('cover_image_path')) {
            // If image selected from media library (check if not empty)
            $data['cover_image'] = $request->input('cover_image_path');
        } else {
            // Preserve old image if no new upload
            unset($data['cover_image']); // Remove from data array to preserve existing
        }

        $store->update($data);

        // ✅ Sync categories & events
        $store->categories()->sync($request->categories ?? []);
        $store->events()->sync($request->events ?? []);

        Cache::forget('nav_category_menu_v1');

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully!');
    }

    public function show(Store $store)
    {
        $store->load(['categories', 'events', 'currentNetwork', 'availableNetwork']);
        return view('admin.stores.show', compact('store'));
    }

    public function updateStatus(Request $request, Store $store)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $store->update(['status' => $request->status]);

        Cache::forget('nav_category_menu_v1');

        $statusText = $request->status ? 'activated' : 'deactivated';
        return redirect()->route('admin.stores.index')->with('success', "Store {$statusText} successfully!");
    }

    public function destroy(Store $store)
    {
        if ($store->store_logo && File::exists(public_path($store->store_logo))) {
            File::delete(public_path($store->store_logo));
        }
        if ($store->cover_image && File::exists(public_path($store->cover_image))) {
            File::delete(public_path($store->cover_image));
        }

        $store->categories()->detach();
        $store->events()->detach();
        $store->delete();

        Cache::forget('nav_category_menu_v1');

        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully!');
    }

    // ✅ Bulk Delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'No stores selected.');
        }

        $stores = Store::whereIn('id', $ids)->get();

        foreach ($stores as $store) {
            if ($store->store_logo && File::exists(public_path($store->store_logo))) {
                File::delete(public_path($store->store_logo));
            }
            if ($store->cover_image && File::exists(public_path($store->cover_image))) {
                File::delete(public_path($store->cover_image));
            }
            $store->categories()->detach();
            $store->events()->detach();
            $store->delete();
        }

        Cache::forget('nav_category_menu_v1');

        return back()->with('success', 'Selected stores deleted successfully.');
    }

    // ✅ Reorder
    public function reorder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:stores,id',
            'order.*.sort_order' => 'required|integer|min:1',
        ]);

        foreach ($request->order as $item) {
            Store::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        Cache::forget('nav_category_menu_v1');

        return response()->json(['status' => 'success']);
    }
}
