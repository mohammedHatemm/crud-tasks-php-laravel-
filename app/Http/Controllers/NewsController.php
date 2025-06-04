<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $news = News::with('category')->latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $news = News::create([
            'name' => $request->name,
            'content' => $request->content,
            'user_id' => auth()->id()
        ]);

        $news->categories()->attach($request->categories);

        return redirect()->route('news.index')
            ->with('success', 'تم إنشاء الخبر بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        //
        return view('admin.news.show', compact('news'));
    }
    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
        $categories = Category::all();
        $selectedCategories = $news->categories->pluck('id')->toArray();
        return view('admin.news.update', compact('news', 'categories', 'selectedCategories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $news->update([
            'name' => $request->name,
            'content' => $request->content,
        ]);

        $news->categories()->sync($request->categories);

        return redirect()->route('news.index')
            ->with('success', 'تم تحديث الخبر بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
        $news->delete();
        return redirect()->route('news.index')
            ->with('success', 'تم حذف الخبر بنجاح');
    }
    public function home(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');
        $date = $request->query('date');

        $news = News::with('categories')
            ->filterByCategory($category)
            ->search($search)
            ->filterByDate($date)
            ->latest()
            ->paginate(9);

        $categories = Category::getCategoryHierarchy();

        return view('home', compact('news', 'categories', 'category', 'search', 'date'));
    }

    public function filter(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');
        $date = $request->query('date');

        $news = News::with('categories')
            ->filterByCategory($category)
            ->search($search)
            ->filterByDate($date)
            ->latest()
            ->paginate(9);

        return response()->json([
            'html' => view('partials.news_items', compact('news'))->render()
        ]);
    }
}
