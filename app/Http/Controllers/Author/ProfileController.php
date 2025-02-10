<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Show the author's profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('author.profile.edit', compact('user'));
    }

    /**
     * Update the author's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'github' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:10240'], // 10MB max
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
            'notification_preferences' => ['array'],
            'notification_preferences.*' => ['boolean']
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            // Process the image
            $image = Image::read(storage_path('app/public/' . $path));
            $image->cover(300, 300);
            $image->save();

            $validated['avatar'] = $path;
        }

        // Handle password update
        if (isset($validated['new_password'])) {
            $validated['password'] = bcrypt($validated['new_password']);
        }

        // Handle social links
        $socialLinks = array_filter([
            'twitter' => $validated['twitter'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'linkedin' => $validated['linkedin'] ?? null,
        ]);

        // Prepare notification preferences
        $notificationPreferences = $request->input('notification_preferences', []);

        // Update the user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'bio' => $validated['bio'],
            'website' => $validated['website'],
            'social_links' => $socialLinks,
            'avatar' => $validated['avatar'] ?? $user->avatar,
            'password' => $validated['password'] ?? $user->password,
        ]);

        return redirect()->route('authorprofile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the author's avatar.
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);

            Cache::tags(['user-profile', $user->id])->flush();
        }

        return redirect()->route('authorprofile.edit')
            ->with('success', 'Avatar removed successfully.');
    }

    /**
     * Show public profile
     */
    public function publicProfile(User $user)
    {
        $cacheKey = "user-profile-$user->id";

        $profile = Cache::tags(['user-profile', $user->id])->remember($cacheKey, 3600, function () use ($user) {
            return $user->load(['posts' => function ($query) {
                $query->published()->latest()->take(5);
            },
            'comments' => function ($query) {
                $query->latest()->take(5);
            }]);
        });

        return view('author.profile.show', compact('user'));
    }

    /**
     * Show profile completion status.
     */
    public function completion()
    {
        $user = Auth::user();
        $completionSteps = [
            'avatar' => !empty($user->avatar),
            'bio' => !empty($user->bio),
            'website' => !empty($user->website),
            'social_links' => !empty(array_filter($user->social_links)),
        ];

        $completionPercentage = (count(array_filter($completionSteps)) / count($completionSteps)) * 100;

        return view('author.profile.completion', [
            'steps' => $completionSteps,
            'percentage' => $completionPercentage
        ]);
    }

    /**
     * Show author statistics.
     */
    public function statistics()
    {
        $user = Auth::user();
        $stats = Cache::tags(['user-stats', $user->id])->remember("user-stats-$user->id", 3600, function () use ($user) {
            return [
                'posts' => [
                    'total' => $user->posts()->count(),
                    'published' => $user->posts()->published()->count(),
                    'draft' => $user->posts()->where('status', 'draft')->count(),
                    'views' => $user->posts()->sum('view_count'),
                ],
                'comments' => [
                    'total' => $user->comments()->count(),
                    'received' => $user->posts()->withCount('comments')->get()->sum('comments_count'),
                ],
                'engagement' => [
                    'avg_comments_per_post' => $user->posts()->withCount('comments')->get()->average('comments_count'),
                    'most_viewed_post' => $user->posts()->orderByDesc('view_count')->first(),
                    'most_commented_post' => $user->posts()->withCount('comments')->orderByDesc('comments_count')->first(),
                ],
                'activity' => [
                    'last_post' => $user->posts()->latest()->first(),
                    'last_comment' => $user->comments()->latest()->first(),
                    'posts_this_month' => $user->posts()->whereMonth('created_at', now()->month)->count(),
                    'comments_this_month' => $user->comments()->whereMonth('created_at', now()->month)->count(),
                ],
                'history' => [
                    'monthly_posts' => $user->posts()
                        ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                        ->whereYear('created_at', now()->year)
                        ->groupBy('month')
                        ->get(),
                    'monthly_views' => $user->posts()
                        ->selectRaw('MONTH(created_at) as month, SUM(view_count) as views')
                        ->whereYear('created_at', now()->year)
                        ->groupBy('month')
                        ->get(),
                ]
            ];
        });

        return view('author.profile.statistics', compact('stats'));
    }

    /**
     * Update notification preferences.
     */
    public function updateNotificationPreferences(Request $request)
    {
        $validated = $request->validate([
            'preferences' => 'array',
            'preferences.*' => 'boolean'
        ]);

        $user = Auth::user();
        $user->update([
            'notification_preferences' => $validated['preferences']
        ]);

        return redirect()->route('author.profile.edit')
            ->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Show profile settings.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('author.profile.settings', compact('user'));
    }
}
