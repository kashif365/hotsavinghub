<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of contact submissions
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Show the specified contact submission
     */
    public function show(Contact $contact)
    {
        // Mark as read if it's new
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }
        
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Update the status of a contact submission
     */
    public function updateStatus(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied,closed'
        ]);

        $contact->update(['status' => $request->status]);

        return back()->with('success', 'Contact status updated successfully!');
    }

    /**
     * Delete a contact submission
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
                        ->with('success', 'Contact submission deleted successfully!');
    }

    /**
     * Bulk delete contact submissions
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id'
        ]);

        Contact::whereIn('id', $request->contact_ids)->delete();

        return back()->with('success', 'Selected contact submissions deleted successfully!');
    }
}
