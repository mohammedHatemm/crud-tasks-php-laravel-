<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('categories')->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }

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

    public function show(News $news)
    {
        return view('admin.news.view', compact('news'));
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        $selectedCategories = $news->categories->pluck('id')->toArray();
        return view('admin.news.update', compact('news', 'categories', 'selectedCategories'));
    }

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
            'content' => $request->content
        ]);

        $news->categories()->sync($request->categories);

        return redirect()->route('news.index')
            ->with('success', 'تم تحديث الخبر بنجاح');
    }

    public function destroy(News $news)
    {
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
