<?php

namespace App\Http\Controllers;

use App\Http\Requests\CuisineControllerStoreRequest;
use App\Http\Requests\CuisineControllerUpdateRequest;
use App\Models\Cuisine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CuisineController extends Controller
{
    public function index(Request $request): View
    {
        $cuisines = Cuisine::all();

        return view('cuisine.index', compact('cuisines'));
    }

    public function show(Request $request, Cuisine $cuisine): View
    {
        $cuisine = Cuisine::find($id);

        return view('cuisine.show', compact('cuisine'));
    }

    public function store(CuisineControllerStoreRequest $request): RedirectResponse
    {
        $cuisine = Cuisine::create($request->validated());

        $request->session()->flash('cuisine.name', $cuisine->name);

        return redirect()->route('cuisine.index');
    }

    public function update(CuisineControllerUpdateRequest $request, Cuisine $cuisine): RedirectResponse
    {
        $cuisine = Cuisine::find($id);


        $cuisine->update($request->validated());

        $request->session()->flash('cuisine.name', $cuisine->name);

        return redirect()->route('cuisine.show', [$cuisine]);
    }

    public function destroy(Request $request, Cuisine $cuisine): RedirectResponse
    {
        $cuisine = Cuisine::find($id);

        $cuisine->delete();

        $request->session()->flash('cuisine.name', $cuisine->name);

        return redirect()->route('cuisine.index');
    }
}
