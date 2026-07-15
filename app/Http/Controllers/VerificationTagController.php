<?php

namespace App\Http\Controllers;

use App\Models\VerificationTag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VerificationTagController extends Controller
{
    public function index()
    {
        try {
            $tags = VerificationTag::orderBy('sort_order')->orderBy('id')->get();
            return view('admin.verification-tags.index', compact('tags'));
        } catch (\Exception $e) {
            // If table doesn't exist or has issues, return empty collection
            \Log::error('VerificationTagController@index error: ' . $e->getMessage());
            $tags = collect([]);
            return view('admin.verification-tags.index', compact('tags'));
        }
    }

    public function create()
    {
        $tag = new VerificationTag([
            'type' => VerificationTag::TYPE_CUSTOM,
            'placement' => 'head_end',
            'is_active' => true,
        ]);

        return view('admin.verification-tags.create', compact('tag'));
    }

    public function store(Request $request)
    {
        try {
            $data = $this->validatedData($request);
            
            // Ensure sort_order is set
            if (empty($data['sort_order']) || $data['sort_order'] === null) {
                $data['sort_order'] = (VerificationTag::max('sort_order') ?? 0) + 1;
            }

            VerificationTag::create($data);

            return redirect()->route('admin.verification-tags.index')->with('success', 'Verification tag created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('VerificationTagController@store error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to create verification tag: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(VerificationTag $verification_tag)
    {
        return view('admin.verification-tags.edit', ['tag' => $verification_tag]);
    }

    public function update(Request $request, VerificationTag $verification_tag)
    {
        try {
            $data = $this->validatedData($request);
            $verification_tag->update($data);

            return redirect()->route('admin.verification-tags.index')->with('success', 'Verification tag updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('VerificationTagController@update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to update verification tag: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(VerificationTag $verification_tag)
    {
        $verification_tag->delete();

        return redirect()->route('admin.verification-tags.index')->with('success', 'Verification tag deleted successfully.');
    }

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'code' => 'required|string',
            'placement' => ['required', Rule::in(VerificationTag::PLACEMENTS)],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [], [
            'code' => 'tag snippet',
            'placement' => 'placement',
            'sort_order' => 'sort order',
        ]);

        // Handle sort_order - convert empty string to null
        if (isset($validated['sort_order']) && $validated['sort_order'] === '') {
            $validated['sort_order'] = null;
        }

        // Handle is_active checkbox - if not present in request, set to false
        $isActive = $request->has('is_active') ? $request->boolean('is_active') : false;

        return array_merge($validated, [
            'type' => VerificationTag::TYPE_CUSTOM,
            'attribute_key' => null,
            'attribute_value' => null,
            'content' => null,
            'script_attributes' => null,
            'is_active' => $isActive,
        ]);
    }
}

