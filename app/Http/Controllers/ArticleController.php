<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(Request $request): View
    {
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('articles.blog-index', [
            'articles' => $articles,
            'title' => 'Articles',
            'meta_description' => 'Browse our latest articles and insights.'
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        // Check if article is published
        if ($article->status !== 'published' || !$article->published_at || $article->published_at->isFuture()) {
            throw new NotFoundHttpException('Article not found or not published.');
        }

        $currentLocale = app()->getLocale();
        $title = $article->getTitle($currentLocale);
        $content = $article->getContent($currentLocale);
        $metaDescription = $article->getMetaDescription($currentLocale);

        return view('articles.blog-show', [
            'article' => $article,
            'title' => $title,
            'content' => $content,
            'meta_description' => $metaDescription ?: substr(strip_tags($content), 0, 155),
        ]);
    }

    /**
     * Get articles by category or tag (for future implementation)
     */
    public function category($category): View
    {
        // This could be implemented later if you add categories/tags to articles
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('articles.blog-index', [
            'articles' => $articles,
            'title' => ucfirst($category) . ' Articles',
            'meta_description' => "Browse articles in the {$category} category."
        ]);
    }
}