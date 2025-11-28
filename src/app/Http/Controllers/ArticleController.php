<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles
     */
    public function index(Request $request)
    {
        $query = Article::published()->with('author');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter (if implemented in future)
        if ($request->has('category') && $request->category) {
            // Future implementation for categories
        }

        $articles = $query->latest('published_at')->paginate(12);

        return view('articles.index', compact('articles'));
    }

    /**
     * Display the specified article
     */
    public function show(Article $article)
    {
        // Check if article is published
        if (!$article->isPublished()) {
            abort(404);
        }


        // Get related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
