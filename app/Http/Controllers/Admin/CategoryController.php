<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('posts')
            ->orderBy('order')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // For parent category selection
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'order' => 'nullable|integer',
            'is_visible' => 'boolean'
        ]);

        // Generate unique slug
        $slug = Str::slug($validated['name']);
        $count = 1;

        while (Category::withTrashed()->where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $count;
            $count++;
        }

        $validated['slug'] = $slug;
        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($value == $category->id) {
                        $fail('A category cannot be its own parent.');
                    }
                }
            ],
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'order' => 'nullable|integer',
            'is_visible' => 'boolean'
        ]);

        // Generate unique slug if name changed
        if ($category->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $count = 1;

            while (Category::withTrashed()->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $count;
                $count++;
            }

            $validated['slug'] = $slug;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Category has children categories.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
