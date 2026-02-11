<?php

namespace App\Policies;

use App\ArticleAbilities;
use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            ArticleAbilities::ARTICLE_CREATE,
            ArticleAbilities::ARTICLE_UPDATE_ANY,
            ArticleAbilities::ARTICLE_DELETE_ANY,
        ]);
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
        if ($user->hasPermission(ArticleAbilities::ARTICLE_CREATE_DENY)) {
            return Response::denyAsNotFound();
        }

        return $user->hasPermission(ArticleAbilities::ARTICLE_CREATE) ?
            Response::allow() :
            Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): Response
    {
        if ($user->didNotWrite($article)) {
            if ($user->hasPermission(ArticleAbilities::ARTICLE_UPDATE_ANY_DENY)) {
                return Response::denyAsNotFound();
            }

            return $user->hasPermission(ArticleAbilities::ARTICLE_UPDATE_ANY) ?
                Response::allow() :
                Response::denyAsNotFound();
        }

        return $user->hasPermission(ArticleAbilities::ARTICLE_UPDATE) ?
            Response::allow() :
            Response::denyAsNotFound();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): Response
    {
        if ($user->didNotWrite($article)) {
            if ($user->hasPermission(ArticleAbilities::ARTICLE_DELETE_ANY_DENY)) {
                return Response::denyAsNotFound();
            }

            return $user->hasPermission(ArticleAbilities::ARTICLE_DELETE_ANY) ?
                Response::allow() :
                Response::denyAsNotFound();
        }

        return $user->hasPermission(ArticleAbilities::ARTICLE_DELETE) ?
            Response::allow() :
            Response::denyAsNotFound();
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
