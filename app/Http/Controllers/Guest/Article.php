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
            $articles->where('title', 'like', '%'.$searchTerm.'%');
        }

        $articles = $articles->latest()->paginate(8);
        $latest_article = ModelsArticle::with('documents')->latest()
            ->take(5)
            ->get();
        $categories = Category::select(['id', 'name', 'slug'])->get();

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
        $article = ModelsArticle::with('category', 'documents')
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

        $categories = Category::select(['id', 'name', 'slug'])->get();

        $data = [
            'title' => 'Article Detail: '.$article->title,
            'description' => $article->description ?? '',
            'article' => $article,
            'categories' => $categories,
            'latest_article' => $latest_article,
        ];

        return view('guest.article.show', $data);
    }

    public function category(Request $request, $category_slug)
    {
        // Ambil kategori berdasarkan slug, jika tidak ditemukan, tampilkan 404
        $category = Category::where('slug', $category_slug)->first();
        if (! $category) {
            abort(404);
        }

        // Query untuk mengambil artikel dalam kategori
        $articlesQuery = ModelsArticle::where('category_id', $category->id)
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

        // Ambil artikel terbaru dari kategori lain
        $latest_article = ModelsArticle::where('category_id', '!=', $category->id)
            ->latest()
            ->take(5)
            ->get();

        // Ambil daftar kategori untuk sidebar
        $categories = Category::select(['id', 'name', 'slug'])->get();

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
