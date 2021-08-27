<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function create(User $user, $request)
    {
        return $user->id === $request->json('data.relationships.authors.data.id');
    }

    public function update(User $user, $article)
    {
        return $article->user->is($user);
    }

    public function delete(User $user, $article)
    {
        return $article->user->is($user);
    }
}
