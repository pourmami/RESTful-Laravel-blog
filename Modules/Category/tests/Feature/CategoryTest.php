<?php

namespace Modules\Category\tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Modules\Category\app\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // ساخت نقش و یوزر ادمین
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /** @test */
    public function admin_can_create_a_category()
    {
        $payload = [
            'name' => 'الکترونیک',
            'slug' => 'electronics',
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/categories', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'الکترونیک']);

        $this->assertDatabaseHas('categories', ['slug' => 'electronics']);
    }

    /** @test */
    public function admin_can_update_a_category()
    {
        $category = Category::factory()->create();

        $payload = [
            'name' => 'جدید',
            'slug' => 'new-slug'
        ];

        $response = $this->actingAs($this->admin)
            ->putJson("/api/categories/{$category->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['slug' => 'new-slug']);

        $this->assertDatabaseHas('categories', ['name' => 'جدید']);
    }

    /** @test */
    public function admin_can_delete_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function guest_cannot_create_category()
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'تست',
            'slug' => 'test'
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    /** @test */
    public function non_admin_cannot_create_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/categories', [
                'name' => 'غیر ادمین',
                'slug' => 'not-admin'
            ]);

        $response->assertStatus(403); // Forbidden
    }
}
