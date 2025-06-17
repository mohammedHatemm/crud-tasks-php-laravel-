<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

class NewsController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }


    public function index()
    {
        $news = News::with('categories', 'user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $news
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer|min:1',
            'category_id.*' => 'integer|exists:categories,id',
        ]);

        $news = News::create([
            'name' => $data['name'],
            'content' => $data['content'],
            'user_id' => Auth::id(),
        ]);

        $news->categories()->attach($data['category_id']);

        return response()->json([
            'status' => 'success',
            'message' => 'News created successfully',
            'data' => $news->load('categories')
        ], 201);
    }


    public function show(News $news)
    {
        $news->load('categories', 'user');

        return response()->json([
            'status' => 'success',
            'data' => $news
        ]);
    }


    public function update(Request $request, News $news)
    {

        if ($news->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|integer|min:1',
            'category_id.*' => 'integer|exists:categories,id',
        ]);

        $news->update($data);

        if (isset($data['category_id'])) {
            $news->categories()->sync($data['category_id']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'News updated successfully',
            'data' => $news->load('categories')
        ]);
    }


    public function destroy(News $news)
    {
        if ($news->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $news->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'News deleted successfully'
        ]);
    }
}
