<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourtRequest;
use App\Models\Category;
use App\Models\Court;
use App\Models\Ground;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    public function index(Request $request)
    {
        $query = Court::query()->with([
            'media',
            'ground',
            'category' => [
                'media',
            ],
        ]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('ground_id')) {
            $query->where('ground_id', $request->ground_id);
        }

        $courts = $query->latest()->paginate(10)->withQueryString();
        $grounds = Ground::query()->active()->get();

        return view('courts.index', compact('courts', 'grounds'));
    }

    public function create()
    {
        $grounds = Ground::query()->active()->get();
        $categories = Category::query()->active()->with('media')->get();

        return view('courts.create', compact('grounds', 'categories'));
    }

    public function store(CourtRequest $request)
    {
        $court = Court::create($request->validated());

        if (! empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $court->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('courts.index')
            ->with('success', __('general.court_created'));
    }

    public function show(Court $court)
    {
        return view('courts.show', compact('court'));
    }

    public function edit(Court $court)
    {
        $grounds = Ground::query()->active()->get();
        $categories = Category::query()->active()->with('media')->get();

        return view('courts.edit', compact('court', 'grounds', 'categories'));
    }

    public function update(CourtRequest $request, Court $court)
    {
        $court->update($request->validated());

        if (! empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $court->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('courts.index')
            ->with('success', __('general.court_updated'));
    }

    public function destroy(Court $court)
    {
        $court->clearMediaCollection('picture');
        $court->delete();

        return redirect()->route('courts.index')
            ->with('success', __('general.court_deleted'));
    }
}
