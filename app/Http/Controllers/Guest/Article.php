<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Article as ModelsArticle;
use App\Models\Tag as ModelTag;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class Article extends Controller
{
    public function index(Request $request)
    {
        $articles = ModelsArticle::with('tags', 'documents');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $articles->where('title', 'like', '%'.$searchTerm.'%');
        }

        $articles = $articles->latest()->paginate(8);
        $latest_article = ModelsArticle::with('documents')->latest()
            ->take(5)
            ->get();
        $categories = ModelTag::withCount('articles')->where('type', 'article')->get();

        $data = [
            'title' => 'Artikel',
            'description' => 'Lihat Artikel terbaru dan informasi yang diterbitkan di '.config('app.name').'.',
            'articles' => $articles,
            'latest_article' => $latest_article,
            'categories' => $categories,
        ];

        return view('guest.article.articles', $data);
    }

    public function show($article_slug)
    {
        $article = ModelsArticle::with('tags', 'documents')
            ->where('slug', $article_slug)
            ->first();

        // Jika artikel tidak ditemukan, lempar 404
        if (! $article) {
            abort(404);
        }

        $latest_article = ModelsArticle::latest()
            ->where('slug', '!=', $article_slug)
            ->take(5)
            ->get();

        $categories = ModelTag::withCount('articles')->where('type', 'article')->get();

        $data = [
            'title' => 'Article Detail: '.$article->title,
            'description' => $article->description ?? '',
            'article' => $article,
            'categories' => $categories,
            'latest_article' => $latest_article,
        ];

        return view('guest.article.show', $data);
    }

    public function category(Request $request, $tag)
    {
        // Ambil tag berdasarkan slug, jika tidak ditemukan, tampilkan 404
        $category = Tag::where('slug->id', $tag)->first();

        if (! $category) {
            abort(404);
        }

        // Query untuk mengambil artikel yang memiliki tag tertentu
        $articlesQuery = ModelsArticle::withAnyTags([$category->name], 'article')
            ->with('tags')
            ->latest();

        // Jika ada pencarian, filter berdasarkan judul
        if ($request->has('search')) {
            $searchTerm = trim(strip_tags($request->search)); // Sanitasi input
            if (! empty($searchTerm)) {
                $articlesQuery->where('title', 'like', '%'.$searchTerm.'%');
            }
        }

        // Ambil artikel dengan pagination
        $articles = $articlesQuery->paginate(10); // Sesuaikan jumlah artikel per halaman

        // Ambil artikel terbaru yang tidak memiliki tag ini
        $latest_article = ModelsArticle::with('tags')
            ->whereDoesntHave('tags', function ($query) use ($category) {
                $query->where('id', $category->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Ambil daftar tag untuk sidebar
        $categories = ModelTag::withCount('articles')->where('type', 'article')->get();

        $data = [
            'title' => 'Artikel Kategori: '.$category->name,
            'description' => '',
            'articles' => $articles, // Sudah dipaginasi
            'categories' => $categories,
            'latest_article' => $latest_article,
            'category' => $category, // Untuk digunakan di Blade
        ];

        return view('guest.article.category', compact('data'));
    }
}
