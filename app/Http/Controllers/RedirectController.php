<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Display a listing of the resource with filters and pagination.
     */
    public function index(Request $request)
    {
        $query = Redirect::query();

        // Filters: type and status
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('status')) {
            $status = $request->string('status') === 'active' ? 1 : 0;
            $query->where('status', $status);
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($w) use ($q) {
                $w->where('old_url', 'like', "%{$q}%")
                  ->orWhere('new_url', 'like', "%{$q}%");
            });
        }

        $redirects = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.redirects.index', compact('redirects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.redirects.form', ['redirect' => new Redirect()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'old_url' => ['required', 'string'],
            'new_url' => ['required', 'string'],
            'type' => ['required', 'in:301,302'],
            'status' => ['nullable', 'boolean'],
        ]);
        $data['status'] = $request->boolean('status', true);

        Redirect::create($data);

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect created');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Redirect $redirect)
    {
        return view('admin.redirects.form', compact('redirect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Redirect $redirect)
    {
        $data = $request->validate([
            'old_url' => ['required', 'string'],
            'new_url' => ['required', 'string'],
            'type' => ['required', 'in:301,302'],
            'status' => ['nullable', 'boolean'],
        ]);
        $data['status'] = $request->boolean('status', true);

        $redirect->update($data);

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Redirect $redirect)
    {
        $redirect->delete();
        return redirect()->route('admin.redirects.index')->with('success', 'Redirect deleted');
    }

    /**
     * Toggle status quick action.
     */
    public function toggle(Redirect $redirect)
    {
        $redirect->update(['status' => !$redirect->status]);
        return back()->with('success', 'Status updated');
    }
}
