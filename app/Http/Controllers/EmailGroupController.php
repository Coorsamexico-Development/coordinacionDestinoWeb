<?php

namespace App\Http\Controllers;

use App\Models\EmailGroup;
use App\Models\EmailGroupRecipient;
use App\Models\Statu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailGroupController extends Controller
{
    public function index()
    {
        $emailGroups = EmailGroup::with('recipients')->get();
        return Inertia::render('Catalogs/EmailGroups/Index', [
            'emailGroups' => $emailGroups
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        EmailGroup::create($request->all());

        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function update(Request $request, EmailGroup $emailGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $emailGroup->update($request->all());

        return redirect()->back()->with('success', 'Group updated successfully.');
    }

    public function destroy(EmailGroup $emailGroup)
    {
        $emailGroup->delete();
        return redirect()->back()->with('success', 'Group deleted successfully.');
    }

    public function addRecipient(Request $request, EmailGroup $emailGroup)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:to,cc,bcc',
        ]);

        $emailGroup->recipients()->create($request->all());

        return redirect()->back()->with('success', 'Recipient added successfully.');
    }

    public function removeRecipient(EmailGroupRecipient $recipient)
    {
        $recipient->delete();
        return redirect()->back()->with('success', 'Recipient removed successfully.');
    }
}
