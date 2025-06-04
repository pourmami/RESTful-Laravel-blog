<?php

namespace App\Console\Commands;

use Modules\Article\app\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';
    protected $description = 'Publish scheduled articles if their time has arrived';

    public function handle(): void
    {
        $count = Article::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        $this->info("{$count} مقاله منتشر شد.");
    }
}
