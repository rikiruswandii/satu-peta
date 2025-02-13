<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Article as ModelsArticle;
use Illuminate\Http\Request;

class Article extends Controller
{
    public function index(Request $request)
    {
        $articles = ModelsArticle::with("category", "documents");

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $articles->where('title', 'like', '%' . $searchTerm . '%');
        }

        $articles = $articles->latest()->paginate(9);

        $data = [
            'title' => 'Artikel',
            'description' => 'Lihat Artikel terbaru dan informasi yang diterbitkan di ' . config('app.name') . '.',
            'articles' => $articles,
        ];
        
        return view('guest.article.articles', $data);
    }
    public function show($article_slug)
    {
        $article = ModelsArticle::with("category", "documents")->where('slug', $article_slug)->first();

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
}
