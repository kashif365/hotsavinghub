<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\ActivityLogService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('category')->ordered()->paginate(10);
        
        // Log the view action
        ActivityLogService::log('view', 'Viewed blogs list', null, null, null, request());
        
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Log the view action
        ActivityLogService::log('view', 'Viewed blog creation form', null, null, null, request());
        
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:255',
            'schema' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'slug' => 'required|string|unique:blogs,slug',
            'featured' => 'nullable|boolean',
            'recommended' => 'nullable|boolean'
        ]);

        $data = $request->all();
        
        // Handle status (checkbox to string conversion)
        $data['status'] = $request->has('status') ? 'published' : 'draft';
        
        // Handle featured and recommended checkboxes
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['recommended'] = $request->has('recommended') ? 1 : 0;
        
        // Handle featured image upload / media library selection
        if ($request->hasFile('featured_image')) {
            $imagePath = $this->imageService->uploadAndConvert(
                $request->file('featured_image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
            $data['featured_image'] = $imagePath;
        } elseif ($request->filled('featured_image_path')) {
            $data['featured_image'] = $request->input('featured_image_path');
        }

        $blog = Blog::create($data);

        // Log the creation action
        ActivityLogService::logCreate($blog, $request);
        
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        // Log the view action
        ActivityLogService::logView($blog, request());
        
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        // Log the view action
        ActivityLogService::log('view', "Viewed blog edit form: {$blog->title}", $blog, null, null, request());
        
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:255',
            'schema' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'slug' => 'required|string|unique:blogs,slug,' . $blog->id,
            'featured' => 'nullable|boolean',
            'recommended' => 'nullable|boolean'
        ]);

        $data = $request->all();
        
        // Handle status (checkbox to string conversion)
        $data['status'] = $request->has('status') ? 'published' : 'draft';
        
        // Handle featured and recommended checkboxes
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['recommended'] = $request->has('recommended') ? 1 : 0;
        
        // Handle featured image upload / media library selection
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
                File::delete(public_path($blog->featured_image));
            }
            
            $imagePath = $this->imageService->uploadAndConvert(
                $request->file('featured_image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
            $data['featured_image'] = $imagePath;
        } elseif ($request->filled('featured_image_path')) {
            $data['featured_image'] = $request->input('featured_image_path');
        } else {
            $data['featured_image'] = $blog->featured_image;
        }

        // Store old values for logging
        $oldValues = $blog->toArray();
        
        $blog->update($data);
        
        // Log the update action
        ActivityLogService::logUpdate($blog, $oldValues, $data, $request);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Log the deletion action before deleting
        ActivityLogService::logDelete($blog, request());
        
        // Delete featured image
        if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
            File::delete(public_path($blog->featured_image));
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully!');
    }

    /**
     * Bulk delete blogs
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) {
            return back()->with('error', 'No blogs selected.');
        }

        $blogs = Blog::whereIn('id', $ids)->get();
        
        // Log bulk deletion
        ActivityLogService::logBulkAction('delete', 'Blog', $blogs->count(), $request);
        
        // Delete featured images
        foreach ($blogs as $blog) {
            if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
                File::delete(public_path($blog->featured_image));
            }
            $blog->delete();
        }

        return back()->with('success', 'Selected blogs deleted successfully.');
    }

    /**
     * Reorder blogs
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'             => 'required|array',
            'order.*.id'        => 'required|integer|exists:blogs,id',
            'order.*.sort_order'=> 'required|integer|min:1',
        ]);

        foreach ($request->order as $item) {
            Blog::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['status' => 'success']);
    }
}
