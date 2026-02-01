<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupCreateRequest;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('groups.index', [
            'groups' => Group::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create', [
            'groups' => Group::all(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupCreateRequest $request)
    {
        $group = Group::create([
            'name' => $request->input('name'),
        ]);

        $group->permissions()->sync($request->input('permissions'));

        return redirect(route('groups.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        return view('groups.edit', [
            'group' => $group,
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $group->update($request->all());
        $group->permissions()->sync($request->input('permissions', []));

        return redirect(route('groups.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
