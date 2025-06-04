<?php

namespace Modules\Article\tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Article\app\Models\Article;
use Modules\Category\app\Models\Category;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');

        $this->actingAs($this->user, 'sanctum');
    }


    /** @test */
    public function it_can_create_article()
    {
        $category = Category::factory()->create();

        $payload = [
            'title' => 'Test Article',
            'slug' => 'test-article',
            'body' => 'This is a test body.',
            'excerpt' => 'This is a test excerpt.',
            'status' => 'draft',
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/articles', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Test Article']);

        $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
    }

    /** @test */
    public function it_can_update_an_article()
    {
        $article = Article::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/articles/{$article->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => 'Updated Title']);
    }

    /** @test */
    public function it_can_delete_an_article()
    {
        $article = Article::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/articles/{$article->id}");

        $response->assertOk();

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
