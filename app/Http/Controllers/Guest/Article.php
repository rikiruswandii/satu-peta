<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Article as ModelsArticle;
use App\Models\Category;
use Illuminate\Http\Request;

class Article extends Controller
{
    public function index(Request $request)
    {
        $articles = ModelsArticle::with('category', 'documents');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $articles->where('title', 'like', '%' . $searchTerm . '%');
        }

        $articles = $articles->latest()->paginate(8);
        $latest_article = ModelsArticle::with('documents')->latest()
            ->take(5)
            ->get();
        $categories = Category::select(['id', 'name', 'slug'])->get();

        $data = [
            'title' => 'Artikel',
            'description' => 'Lihat Artikel terbaru dan informasi yang diterbitkan di ' . config('app.name') . '.',
            'articles' => $articles,
            'latest_article' => $latest_article,
            'categories' => $categories,
        ];

        return view('guest.article.articles', $data);
    }

    public function show($article_slug)
    {
        $article = ModelsArticle::with('category', 'documents')->where('slug', $article_slug)->first();

        $latest_article = ModelsArticle::latest()
            ->where('slug', '!=', $article_slug)
            ->take(5)
            ->get();

        $data = [
            'title' => 'Article',
            'description' => '',
            'article' => $article,
            'latest_article' => $latest_article,
        ];

        return view('guest.article.show', $data);
    }
    public function category($category_slug)
    {
        // Mengambil kategori dengan artikel terkait
        $category = Category::with('artikel')->where('slug', $category_slug)->firstOrFail();

        // Mengambil artikel terbaru dari kategori lain
        $latest_article = ModelsArticle::where('category_id', '!=', $category->id)
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'title' => 'Artikel Kategori',
            'description' => '',
            'category' => $category,
            'latest_article' => $latest_article,
        ];

        return view('guest.article.category', $data);
    }
}