<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\app\Models\Article;
use Modules\Category\app\Models\Category;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'body' => $this->faker->paragraph,
            'excerpt' => $this->faker->text(100),
            'status' => 'published',
            'published_at' => now()->addDay(),
            'category_id' => 3,
            'user_id' => 1,
        ];
    }
}
