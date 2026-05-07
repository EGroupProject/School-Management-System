<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * List all groups
     */
    public function index()
    {
        $groups = Group::withCount('students')->get();
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Store new group
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:groups,name',
        ]);

        Group::create([
            'name' => $request->name,
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update group
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|unique:groups,name,' . $group->id,
        ]);

        $group->update([
            'name' => $request->name,
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully');
    }

    /**
     * Delete group
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully');
    }
}
