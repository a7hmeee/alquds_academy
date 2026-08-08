<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::latest()->paginate(10);
        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'type' => ['required','in:mosque,school,university,other'],
        ]);

        Organization::create($data);

        return redirect()->route('organizations.index')
            ->with('success', 'تمت إضافة الجهة بنجاح');
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'type' => ['required','in:mosque,school,university,other'],
        ]);

        $organization->update($data);

        return redirect()->route('organizations.index')
            ->with('success', 'تم تحديث الجهة بنجاح');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('organizations.index')
            ->with('success', 'تم حذف الجهة بنجاح');
    }
}