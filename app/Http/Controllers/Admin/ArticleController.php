<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lunar\Models\Language;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::ownedByUser()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.articles.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $validation = [
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ];

        $languages = Language::all();
        foreach ($languages as $language) {
            $validation["title.{$language->code}"] = 'required|string|max:255';
            $validation["content.{$language->code}"] = 'required|string';
            $validation["meta_description.{$language->code}"] = 'nullable|string|max:255';
        }

        $data = $request->validate($validation);

        // Generate slug from default language title
        $defaultLanguage = Language::where('default', true)->first();
        $defaultTitle = $data['title'][$defaultLanguage->code] ?? '';

        $article = new Article();
        $slug = $article->generateSlug($defaultTitle);

        // Build attribute_data structure
        $attributeData = [];
        foreach (['title', 'content', 'meta_description'] as $field) {
            if (isset($data[$field])) {
                $attributeData[$field] = $data[$field];
            }
        }

        $article = Article::create([
            'attribute_data' => $attributeData,
            'slug' => $slug,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null,
            'owner_id' => Auth::user()->owner_id,
        ]);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        $languages = Language::all();
        return view('admin.articles.show', compact('article', 'languages'));
    }

    public function edit(Article $article)
    {
        $languages = Language::all();
        return view('admin.articles.edit', compact('article', 'languages'));
    }

    public function update(Request $request, Article $article)
    {
        $validation = [
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ];

        $languages = Language::all();
        foreach ($languages as $language) {
            $validation["title.{$language->code}"] = 'required|string|max:255';
            $validation["content.{$language->code}"] = 'required|string';
            $validation["meta_description.{$language->code}"] = 'nullable|string|max:255';
        }

        $data = $request->validate($validation);

        // Build attribute_data structure
        $attributeData = $article->attribute_data ?? [];
        foreach (['title', 'content', 'meta_description'] as $field) {
            if (isset($data[$field])) {
                $attributeData[$field] = $data[$field];
            }
        }

        // Auto-generate slug from default language title
        $defaultLanguage = Language::where('default', true)->first();
        $defaultTitle = $data['title'][$defaultLanguage->code] ?? '';
        $slug = $article->generateSlug($defaultTitle);

        $article->update([
            'attribute_data' => $attributeData,
            'slug' => $slug,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($data['published_at'] ?? $article->published_at ?? now()) : null,
        ]);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}