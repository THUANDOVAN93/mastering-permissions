<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        Gate::define('access-admin', function (User $user) {
//            return $user->hasRole('admin') || $user->hasRole('author')
//                || $user->hasRole('editor');
//        });
//
//        Gate::define('manage-articles', function (User $user, Article $article) {
//            return ($user->hasRole('admin') || $user->hasRole('editor'))
//                || ($user->hasRole('author') && $user->id === $article->author_id);
//        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasAnPermission(['user:create', 'permission:create']);
        });

        Blade::directive('role', function ($expression) {
            return "<?php if (Auth::user()->hasAnyRoles([$expression])): ?>";
        });

        Blade::directive('endrole', function ($expression) {
            return "<?php endif; ?>";
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
