<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
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

    /**
     * Default social links structure
     */
    const DEFAULT_SOCIAL_LINKS = [
        'twitter' => null,
        'facebook' => null,
        'linkedin' => null,
        'github' => null,
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

            if (!app()->runningInConsole()) {
                if (in_array($user->role, ['admin', 'editor']) &&
                    (!auth()->user() || !auth()->user()->isAdmin())) {
                    throw new Exception('Unauthorized role assignment.');
                }
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
        'last_login_at',
        'notification_preferences',
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
        'notification_preferences' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Get formatted profile information
     */
    public function getProfileAttribute(): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'website' => $this->website,
            'avatar_url' => $this->avatar_url,
            'social_links' => $this->social_links ?? self::DEFAULT_SOCIAL_LINKS,
            'post_count' => $this->posts()->count(),
            'comment_count' => $this->comments()->count(),
            'member_since' => $this->created_at->format('F Y'),
            'last_active' => $this->last_login_at ? $this->last_login_at->diffForHumans() : 'Never',
        ];
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get default notification preferences
     */
    public function getDefaultNotificationPreferences(): array
    {
        return [
            'email_notifications' => true,
            'comment_notifications' => true,
            'reply_notifications' => true,
            'mention_notifications' => true
        ];
    }

    /**
     * Get notification preferences with defaults
     */
    public function getNotificationPreferencesAttribute($value): array
    {
        return array_merge(
            $this->getDefaultNotificationPreferences(),
            $value ? json_decode($value, true) : []
        );
    }

    /**
     * Get social links with defaults
     */
    public function getSocialLinksAttribute($value): array
    {
        return array_merge(
            self::DEFAULT_SOCIAL_LINKS,
            $value ? json_decode($value, true) : []
        );
    }

    /**
     * Check if a user has completed their profile
     */
    public function hasCompletedProfile(): bool
    {
        return !empty($this->bio)
            && !empty($this->avatar)
            && !empty($this->website)
            && !empty(array_filter($this->social_links));
    }


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

    /**
     * Get the route key name for Laravel's implicit model binding
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    /**
     * Get public profile URL
     */
    public function getProfileUrlAttribute(): string
    {
        return route('author.profile.show', $this->username);
    }

    /*
     * Determine if the user can access the Filament Panel.
     */
    /**
     * @throws Exception
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access based on roles
        return match ($panel->getId()) {
            'admin' => $this->isAdmin(),
            'editor' => $this->isEditor() || $this->isAdmin(),
            default => false,
        };
    }

    /*
     * Get the filament avatar URL.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
