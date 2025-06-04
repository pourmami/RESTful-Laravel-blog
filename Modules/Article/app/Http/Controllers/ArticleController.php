<?php

namespace Modules\Article\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Transformers\ArticleResource;
use Modules\Article\Http\Requests\StoreArticleRequest;

class ArticleController extends Controller
{
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article = Article::create($data);

        return response()->json(new ArticleResource($article), 201);
    }
}
