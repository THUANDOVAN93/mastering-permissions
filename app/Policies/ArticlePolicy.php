<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRoles(['admin', 'editor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasAnyRoles(['admin', 'author']) ?
            Response::allow() :
            Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): Response
    {
        if ($user->hasAnyRoles(['admin', 'editor'])) {
            return Response::allow();
        }

        return $user->hasRole('author') && $user->id === $article->author_id ?
            Response::allow() :
            Response::deny('You do not have permission to update this article.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): Response
    {
        if ($user->hasAnyRoles(['admin', 'editor'])) {
            return Response::allow();
        }

        return $user->hasRole('author') && $user->id === $article->author_id ?
            Response::allow() :
            Response::deny('You do not have permission to update this article.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return false;
    }
}
