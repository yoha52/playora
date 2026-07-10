<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroundRequest;
use App\Models\Ground;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroundController extends Controller
{
    public function index(Request $request)
    {
        $query = Ground::query()->with('media');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $grounds = $query->latest()->paginate(10)->withQueryString();

        return view('grounds.index', compact('grounds'));
    }

    public function create()
    {
        return view('grounds.create');
    }

    public function store(GroundRequest $request)
    {
        $ground = Ground::create($request->validated());

        if (! empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $ground->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('grounds.index')
            ->with('success', __('general.ground_created'));
    }

    public function show(Ground $ground)
    {
        return view('grounds.show', compact('ground'));
    }

    public function edit(Ground $ground)
    {
        return view('grounds.edit', compact('ground'));
    }

    public function update(GroundRequest $request, Ground $ground)
    {
        $ground->update($request->validated());

        if (! empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $ground->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('grounds.index')
            ->with('success', __('general.ground_updated'));
    }

    public function destroy(Ground $ground)
    {
        $ground->clearMediaCollection('picture');
        $ground->delete();

        return redirect()->route('grounds.index')
            ->with('success', __('general.ground_deleted'));
    }
}
