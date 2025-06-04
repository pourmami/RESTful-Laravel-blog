<?php

namespace Modules\Article\app\Http\Controllers;

use App\CacheTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Transformers\ArticleResource;
use Modules\Article\app\Http\Requests\StoreArticleRequest;
use Modules\Article\app\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    use CacheTrait, AuthorizesRequests;

    public function index(Request $request): JsonResponse
    {
        $cache_key = 'articles_index_' . md5($request->fullUrl());
        $articles = Cache::remember($cache_key, 60, function () use ($request) {
            return Article::whereStatus('published')->filter($request->all())->with('category', 'author')->paginate(10);
        });

        $this->remember_dynamic_keys('article_cache_keys', $cache_key);

        return response()->json(ArticleResource::collection($articles));
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = Article::create([...$request->validated(), 'user_id' => auth()->id()]);
        return response()->json(new ArticleResource($article), 201);
    }

    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $this->authorize('update', $article);
        $article->update($request->validated());

        return response()->json(new ArticleResource($article));
    }

    public function destroy(Article $article): JsonResponse
    {
        $this->authorize('delete', $article);
        $article->delete();
        return response()->json(['message' => 'مقاله حذف شد.']);
    }
}
