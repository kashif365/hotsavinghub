<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Networks;

class NetworksController extends Controller
{
    // Display Networks page
    public function index()
    {
        $networks = Networks::orderBy('sort_order')->get();
        return view('admin.networks.index', compact('networks'));
    }

    // Store new network
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'affiliate_id' => 'nullable|string|max:255',
            'status'       => 'required|boolean'
        ]);

        $lastOrder = Networks::max('sort_order') ?? 0;

        Networks::create([
            'name'         => $request->name,
            'affiliate_id' => $request->affiliate_id,
            'status'       => $request->boolean('status'), // ensures 0 or 1
            'sort_order'   => $lastOrder + 1
        ]);

        return redirect()->back()->with('success', 'Network added successfully.');
    }

    // Update existing network
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'affiliate_id' => 'nullable|string|max:255',
            'status'       => 'required|boolean'
        ]);

        $network = Networks::findOrFail($id);

        $network->update([
            'name'         => $request->name,
            'affiliate_id' => $request->affiliate_id,
            'status'       => $request->boolean('status')
        ]);

        return redirect()
            ->route('admin.networks.index')
            ->with('success', 'Network updated successfully.');
    }

    // Delete single network
public function destroy($id)
{
    $network = Networks::findOrFail($id);
    $network->delete();

    return redirect()
        ->route('admin.networks.index')
        ->with('success', 'Network deleted successfully.');
}


    // Bulk delete networks
    public function bulkDelete(Request $request)
    {
        if (!$request->has('ids') || count($request->ids) === 0) {
            return redirect()
                ->route('admin.networks.index')
                ->with('error', 'No networks selected.');
        }

        Networks::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('admin.networks.index')
            ->with('success', 'Selected networks deleted successfully.');
    }

    // Reorder networks (AJAX)
    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order'               => 'required|array',
            'order.*.id'          => 'required|integer|exists:networks,id',
            'order.*.sort_order'  => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['order'] as $item) {
                Networks::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }
        });

        return response()->json(['status' => 'success']);
    }
}
