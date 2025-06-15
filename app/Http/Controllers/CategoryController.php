<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewCategoryNotification;
use Illuminate\Support\Facades\Notification;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)

    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $category = Category::create($request->all());
        // Notify all users about the new category

        User::all()->each(function ($user) use ($category) {
            $user->notify(new NewCategoryNotification($category));
        });
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        $category->load(['parent.parent.parent.parent', 'children', 'news']);
        return view('admin.categories.view', compact('category'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        $category = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
