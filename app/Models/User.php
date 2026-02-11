<?php

namespace App\Models;

use App\ArticleAbilities;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;

/**
 * @property array<int, string> $permissions
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    protected $attributes = [
        'permissions' => '[]',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function hasRole(string $authCode): bool
    {
        return $this->roles()->where('auth_code', $authCode)->exists();
    }

    public function getAllPermissions()
    {
        if (Auth::user()->id === $this->id && Context::hasHidden('permissions')) {
            return Context::getHidden('permissions');
        }

        $groupPermissions = $this->groups()->with('permissions')->get()
            ->pluck('permissions')->flatten()->pluck('auth_code');

        $permissions = collect($this->permissions);

        return $groupPermissions->merge($permissions)->unique()->map(function ($item) {
            return strtolower($item);
        });
    }

    public function hasPermission($permission): bool
    {
        if ($permission instanceof ArticleAbilities) {
            return $this->getAllPermissions()->contains($permission);
        }

        return $this->getAllPermissions()->contains(strtolower($permission));
    }

    public function hasAnyPermission(array $permissions): bool
    {
        $pers = array_map(function ($item) {
            if ($item instanceof ArticleAbilities) {
                return $item;
            }

            return strtolower($item);
        }, $permissions);

        return $this->getAllPermissions()->intersect($pers)->isNotEmpty();
    }

    public function wrote(Article $article): bool
    {
        return $this->id === $article->author_id;
    }

    public function didNotWrite(Article $article): bool
    {
        return $this->id !== $article->author_id;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }
}
