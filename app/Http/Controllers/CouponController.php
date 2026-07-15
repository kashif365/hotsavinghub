<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Store;
use App\Models\Category;
use App\Services\ActivityLogService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Events;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CouponController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $coupons = Coupon::with(['event', 'store'])->orderBy('sort_order')->get();
        
        // Log the view action
        ActivityLogService::log('view', 'Viewed coupons list', null, null, null, request());
        
        return view('admin.coupons.index', compact('coupons'));
    }
    
   public function create()
{
    $events = Events::all();
    $stores = Store::where('status', 1)->orderBy('store_name')->get();
    $categories = Category::where('status', 1)->orderBy('category_name')->get();
    
    // Log the view action
    ActivityLogService::log('view', 'Viewed coupon creation form', null, null, null, request());
    
    return view('admin.coupons.create', compact('events', 'stores', 'categories'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'coupon_title'   => 'required|string|max:255',
        'brand_store'    => 'required|string|max:255',
        'coupon_code'    => 'nullable|string|max:255',
        'event_id'       => 'nullable|exists:events,id', // foreign key validation
        'category_id'    => 'nullable|exists:categories,id', // foreign key validation
        'submitted_by'   => 'nullable|string|max:255',
        'affiliate_url'  => 'nullable|string|max:255',
        'date_available' => 'nullable|date',
        'date_expiry'    => 'nullable|date',
        'description'    => 'nullable|string',
        'terms'          => 'nullable|string',
        'cover_logo'     => 'nullable|image|max:2048',
    ]);

    $validated['exclusive']   = $request->boolean('exclusive');
    $validated['featured']    = $request->boolean('featured');
    $validated['recommended'] = $request->boolean('recommended');
    $validated['verified']    = $request->boolean('verified');
    $validated['hot_deals']   = $request->boolean('hot_deals');
    $validated['student_offer'] = $request->boolean('student_offer');
    $validated['status']      = $request->boolean('status');
    $validated['expiry_soon'] = $request->boolean('expiry_soon');

    // Cover Logo Upload - Use ImageService for auto WebP conversion
    if ($request->hasFile('cover_logo')) {
        $path = $this->imageService->uploadAndConvert(
            $request->file('cover_logo'),
            'uploads',
            ['quality' => 100, 'preserve_original' => true]
        );
        $validated['cover_logo'] = $path;
    } elseif ($request->has('cover_logo_path')) {
        // If image selected from media library
        $validated['cover_logo'] = $request->input('cover_logo_path');
    }

    $coupon = Coupon::create($validated);
    
    // Log the creation action
    ActivityLogService::logCreate($coupon, request());

    return redirect()->route('admin.coupons.index')->with('success', 'Coupon Created Successfully');
}

    public function edit(Coupon $coupon)
    {
        $events = Events::all();
        $stores = Store::where('status', 1)->orderBy('store_name')->get();
        $categories = Category::where('status', 1)->orderBy('category_name')->get();
        
        // Log the view action
        ActivityLogService::logView($coupon, request());
        
        return view('admin.coupons.edit', compact('coupon', 'events', 'stores', 'categories'));
    }

public function update(Request $request, $id)
{
    $coupon = Coupon::findOrFail($id);
    
    // Store old values for logging
    $oldValues = $coupon->toArray();

    // Validate cover_logo separately if file is uploaded
    if ($request->hasFile('cover_logo')) {
        $request->validate([
            'cover_logo' => 'image|max:2048',
        ]);
    }

    $validated = $request->validate([
        'coupon_title'   => 'required|string|max:255',
        'brand_store'    => 'required|string|max:255',
        'coupon_code'    => 'nullable|string|max:255',
        'event_id'       => 'nullable|exists:events,id', // foreign key validation
        'category_id'    => 'nullable|exists:categories,id', // foreign key validation
        'submitted_by'   => 'nullable|string|max:255',
        'affiliate_url'  => 'nullable|string|max:255',
        'date_available' => 'nullable|date',
        'date_expiry'    => 'nullable|date',
        'description'    => 'nullable|string',
        'terms'          => 'nullable|string',
    ]);

    $validated['exclusive']   = $request->boolean('exclusive');
    $validated['featured']    = $request->boolean('featured');
    $validated['recommended'] = $request->boolean('recommended');
    $validated['verified']    = $request->boolean('verified');
    $validated['hot_deals']   = $request->boolean('hot_deals');
    $validated['student_offer'] = $request->boolean('student_offer');
    $validated['status']      = $request->boolean('status');
    $validated['expiry_soon'] = $request->boolean('expiry_soon');

    // Cover Logo Update - Use ImageService for auto WebP conversion
    if ($request->hasFile('cover_logo')) {
        if ($coupon->cover_logo && File::exists(public_path($coupon->cover_logo))) {
            File::delete(public_path($coupon->cover_logo));
        }
        $path = $this->imageService->uploadAndConvert(
            $request->file('cover_logo'),
            'uploads',
            ['quality' => 100, 'preserve_original' => true]
        );
        $validated['cover_logo'] = $path;
    } elseif ($request->filled('cover_logo_path')) {
        // If image selected from media library (check if not empty)
        $validated['cover_logo'] = $request->input('cover_logo_path');
    } else {
        // Preserve old image if no new upload - don't add to validated array
        // Existing image will remain unchanged
    }

    $coupon->update($validated);
    
    // Log the update action
    ActivityLogService::logUpdate($coupon, $oldValues, $coupon->fresh()->toArray(), request());

    return redirect()->route('admin.coupons.index')->with('success', 'Coupon Updated Successfully');
}

    public function show(Coupon $coupon)
    {
        $coupon->load(['event', 'store']);
        
        // Log the view action
        ActivityLogService::logView($coupon, request());
        
        return view('admin.coupons.show', compact('coupon'));
    }

    public function updateStatus(Request $request, Coupon $coupon)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $coupon->update(['status' => $request->status]);
        
        $statusText = $request->status ? 'activated' : 'deactivated';
        return redirect()->route('admin.coupons.index')->with('success', "Coupon {$statusText} successfully!");
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        if ($coupon->cover_logo && File::exists(public_path($coupon->cover_logo))) {
            File::delete(public_path($coupon->cover_logo));
        }

        // Log the deletion action before deleting
        ActivityLogService::logDelete($coupon, request());

        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon Deleted Successfully');
    }

        public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            Coupon::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }
        return response()->json(['status' => 'success']);
    }
    
    public function bulkDelete(Request $request)
{
    $ids = $request->ids;
    if (!$ids) {
        return redirect()->back()->with('error', 'No records selected');
    }

    $coupons = Coupon::whereIn('id', $ids)->get();
    
    // Log the bulk deletion action
    ActivityLogService::logBulkAction('delete', 'Coupon', count($coupons), request());
    
    foreach ($coupons as $coupon) {
        if ($coupon->cover_logo && File::exists(public_path($coupon->cover_logo))) {
            File::delete(public_path($coupon->cover_logo));
        }
        $coupon->delete();
    }

    return redirect()->back()->with('success', 'Selected coupons deleted successfully');
}


}
