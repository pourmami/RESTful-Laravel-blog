<?php

namespace Modules\Category\app\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent_id'];

    protected $hidden = [ 'created_at', 'updated_at'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function first_child(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function children(): Builder|HasMany
    {
        return $this->first_child()->with('children');
    }
}
