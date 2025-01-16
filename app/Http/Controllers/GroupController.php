<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupControllerStoreRequest;
use App\Http\Requests\GroupControllerUpdateRequest;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(Request $request): View
    {
        $groups = Group::all();

        return view('group.index', compact('groups'));
    }

    public function show(Request $request, Group $group): View
    {
        $group = Group::find($id);

        return view('group.show', compact('group'));
    }

    public function store(GroupControllerStoreRequest $request): RedirectResponse
    {
        $group = Group::create($request->validated());

        $request->session()->flash('group.name', $group->name);

        return redirect()->route('group.index');
    }

    public function update(GroupControllerUpdateRequest $request, Group $group): RedirectResponse
    {
        $group = Group::find($id);


        $group->update($request->validated());

        $request->session()->flash('group.name', $group->name);

        return redirect()->route('group.show', [$group]);
    }

    public function destroy(Request $request, Group $group): RedirectResponse
    {
        $group = Group::find($id);

        $group->delete();

        $request->session()->flash('group.name', $group->name);

        return redirect()->route('group.index');
    }
}
