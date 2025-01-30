<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const ROLES = [
        'admin' => 'Administrator',
        'editor' => 'Editor',
        'author' => 'Author',
    ];

    protected $attributes = [
        'role' => 'author' // Default role
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'author';
            }

            if (in_array($user->role, ['admin', 'editor']) &&
                (!auth()->user() || !auth()->user()->isAdmin())) {
                throw new Exception('Unauthorized role assignment.');
            }
        });
    }


    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'avatar',
        'role',
        'bio',
        'website',
        'social_links',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'social_links' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory|UserFactory
    {
        return UserFactory::new();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        return in_array($this->role, $roles);
    }
}
