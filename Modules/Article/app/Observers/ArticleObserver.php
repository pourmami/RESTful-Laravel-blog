<?php

namespace Modules\Article\app\Observers;

use App\CacheTrait;
use Modules\Article\app\Models\Article;

class ArticleObserver
{
    use CacheTrait;

    public function created(Article $article): void
    {
        $this->forget_dynamic_keys('article_cache_keys');
    }

    public function updated(Article $article): void
    {
        $this->forget_dynamic_keys('article_cache_keys');
    }

    public function deleted(Article $article): void
    {
        $this->forget_dynamic_keys('article_cache_keys');
    }
}
