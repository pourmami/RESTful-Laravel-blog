<?php

namespace Modules\Article\app\Policies;

use App\Models\User;
use Modules\Article\app\Models\Article;

class ArticlePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('create articles');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasRole('admin');
    }
}
