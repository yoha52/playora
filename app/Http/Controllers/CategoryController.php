<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()
            ->with([
                'media',
            ]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        //
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if (!empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $category->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return response()->json($category->load('media'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        if (!empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $category->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->clearMediaCollection('picture');
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
