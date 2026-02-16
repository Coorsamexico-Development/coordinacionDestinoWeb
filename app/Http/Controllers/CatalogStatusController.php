<?php

namespace App\Http\Controllers;

use App\Models\EmailGroup;
use App\Models\Statu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogStatusController extends Controller
{
    public function index()
    {
        $statuses = Statu::with('emailGroup')->get();
        $emailGroups = EmailGroup::where('active', true)->get();

        return Inertia::render('Catalogs/Status/Index', [
            'statuses' => $statuses,
            'emailGroups' => $emailGroups
        ]);
    }

    public function update(Request $request, Statu $status)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'email_group_id' => 'nullable|exists:email_groups,id',
        ]);

        $status->update($request->all());

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
