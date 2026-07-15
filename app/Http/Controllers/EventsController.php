<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Customer;
use App\Notifications\NewEventNotification;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class EventsController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        // Coupons ki tarah sort_order par hi listing
        $events = Events::with(['coupons', 'stores'])->orderBy('sort_order')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name'          => 'required|string|max:255',
            'event_type'          => 'nullable|string|max:255',
            'date_available'      => 'nullable|date',
            'date_expiry'         => 'nullable|date',
            'seo_url'             => 'required|string|max:255|unique:events,seo_url',
            'meta_title'          => 'nullable|string|max:255',
            'meta_keywords'       => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string',
            'event_short_content' => 'nullable|string',
            'detail_description'  => 'nullable|string',
            'front_image'         => 'nullable|image|max:2048',
            'button_icon'         => 'nullable|image|max:2048',
            'cover_image'         => 'nullable|image|max:2048',
            'no_coupon_cover'     => 'nullable|image|max:2048',
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['show_footer'] = $request->boolean('show_footer');

        // Files upload - Use ImageService for auto WebP conversion
        foreach (['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'] as $field) {
            if ($request->hasFile($field)) {
                // Use ImageService to auto-convert to WebP and optimize
                $path = $this->imageService->uploadAndConvert(
                    $request->file($field),
                    'uploads',
                    ['quality' => 100, 'preserve_original' => true]
                );
                $validated[$field] = $path;
            } elseif ($request->has($field . '_path')) {
                // If image selected from media library (hidden input)
                $validated[$field] = $request->input($field . '_path');
            }
        }

        // Last sort_order + 1 (empty table handle)
        $last = Events::max('sort_order') ?? 0;
        $validated['sort_order'] = $last + 1;

        $event = Events::create($validated);

        // Send email notifications to all subscribed customers
        $subscribedCustomers = Customer::subscribed()->get();
        if ($subscribedCustomers->count() > 0) {
            Notification::send($subscribedCustomers, new NewEventNotification($event));
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully and notifications sent to ' . $subscribedCustomers->count() . ' subscribers.');
    }

    public function edit(Events $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Events $event)
    {
        // Validate image fields separately if files are uploaded
        $imageFields = ['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $request->validate([
                    $field => 'image|max:2048',
                ]);
            }
        }

        $validated = $request->validate([
            'event_name'          => 'required|string|max:255',
            'event_type'          => 'nullable|string|max:255',
            'date_available'      => 'nullable|date',
            'date_expiry'         => 'nullable|date',
            'seo_url'             => 'required|string|max:255|unique:events,seo_url,' . $event->id,
            'meta_title'          => 'nullable|string|max:255',
            'meta_keywords'       => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string',
            'event_short_content' => 'nullable|string',
            'detail_description'  => 'nullable|string',
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['show_footer'] = $request->boolean('show_footer');

        // Replace files - Use ImageService for auto WebP conversion
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists (check multiple possible locations)
                if ($event->{$field}) {
                    // Check in public/uploads
                    $oldPath1 = public_path($event->{$field});
                    // Check in storage/app/public/uploads (old location)
                    $oldPath2 = storage_path('app/public/' . $event->{$field});
                    // Check if path starts with storage/
                    if (str_starts_with($event->{$field}, 'storage/')) {
                        $oldPath3 = public_path($event->{$field});
                    } else {
                        $oldPath3 = null;
                    }
                    
                    if (File::exists($oldPath1)) {
                        File::delete($oldPath1);
                    } elseif (File::exists($oldPath2)) {
                        File::delete($oldPath2);
                    } elseif ($oldPath3 && File::exists($oldPath3)) {
                        File::delete($oldPath3);
                    }
                }
                // Use ImageService to auto-convert to WebP and optimize
                $path = $this->imageService->uploadAndConvert(
                    $request->file($field),
                    'uploads',
                    ['quality' => 100, 'preserve_original' => true]
                );
                $validated[$field] = $path;
            } elseif ($request->filled($field . '_path')) {
                // If image selected from media library (check if not empty)
                $validated[$field] = $request->input($field . '_path');
            } else {
                // Preserve old image if no new upload - don't add to validated array
                // Existing image will remain unchanged
            }
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function show(Events $event)
    {
        $event->load(['coupons', 'stores']);
        return view('admin.events.show', compact('event'));
    }

    public function updateStatus(Request $request, Events $event)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $event->update(['status' => $request->status]);
        
        $statusText = $request->status ? 'activated' : 'deactivated';
        return redirect()->route('admin.events.index')->with('success', "Event {$statusText} successfully!");
    }

    public function destroy(Events $event)
    {
        // Optional: files cleanup
        foreach (['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'] as $field) {
            if ($event->{$field} && File::exists(public_path($event->{$field}))) {
                File::delete(public_path($event->{$field}));
            }
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    // ===== Bulk Delete (Coupons jaisa) =====
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) {
            return back()->with('error', 'No events selected.');
        }

        $events = Events::whereIn('id', $ids)->get();

        // Optional: files cleanup per event
        foreach ($events as $event) {
            foreach (['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'] as $field) {
                if ($event->{$field} && File::exists(public_path($event->{$field}))) {
                    File::delete(public_path($event->{$field}));
                }
            }
            $event->delete();
        }

        return back()->with('success', 'Selected events deleted successfully.');
    }

    // ===== Row Reorder (JS se aane wale keys: id + sort_order) =====
    public function reorder(Request $request)
    {
        $request->validate([
            'order'             => 'required|array',
            'order.*.id'        => 'required|integer|exists:events,id',
            'order.*.sort_order'=> 'required|integer|min:1',
        ]);

        foreach ($request->order as $item) {
            Events::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['status' => 'success']);
    }
}
