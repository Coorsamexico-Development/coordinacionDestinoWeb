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
        $emailGroups = EmailGroup::with(['recipients', 'status'])->get();
        $statuses = Statu::all();
        return Inertia::render('Catalogs/EmailGroups/Index', [
            'emailGroups' => $emailGroups,
            'statuses' => $statuses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $emailGroup = EmailGroup::create($request->except('status_ids'));

        if ($request->has('status_ids')) {
            Statu::whereIn('id', $request->status_ids)->update(['email_group_id' => $emailGroup->id]);
        }

        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function update(Request $request, EmailGroup $emailGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $emailGroup->update($request->except('status_ids'));

        if ($request->has('status_ids')) {
            // Dissociate old statuses
            Statu::where('email_group_id', $emailGroup->id)->update(['email_group_id' => null]);
            // Associate new statuses
            Statu::whereIn('id', $request->status_ids)->update(['email_group_id' => $emailGroup->id]);
        }

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
