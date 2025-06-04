<?php
namespace Modules\Article\app\Models;

use App\Models\User;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\app\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'status', 'body', 'excerpt', 'published_at', 'archived_at', 'category_id', 'user_id'
    ];

    protected $dates = ['published_at'];

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($query, array $filters): void
    {
        $escapeLike = fn($value) => addcslashes($value, '%_');

        $query
            ->when($filters['author_name'] ?? null, function ($q, $authorName) use ($escapeLike) {
                $escaped = $escapeLike($authorName);
                $pattern = '%' . $escaped . '%';
                $q->whereHas('author', function ($q) use ($pattern) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$pattern]);
                });
            })
            ->when($filters['category_name'] ?? null, function ($q, $categoryName) use ($escapeLike) {
                $escaped = $escapeLike($categoryName);
                $q->whereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$escaped}%"));
            })
            ->when($filters['search'] ?? null, function ($q, $search) use ($escapeLike) {
                $escaped = $escapeLike($search);
                $q->where(function ($q) use ($escaped) {
                    $q->where('title', 'LIKE', "%{$escaped}%")
                        ->orWhere('body', 'LIKE', "%{$escaped}%");
                });
            });
    }
}
