<?php
namespace Modules\Article\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\app\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'body', 'excerpt', 'published_at', 'category_id', 'user_id'
    ];

    protected $dates = ['published_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
