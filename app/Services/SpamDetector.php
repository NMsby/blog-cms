<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;

class SpamDetector
{
    private const SPAM_KEYWORDS = [
        'buy now',
        'click here',
        'earn money',
        'free offer',
        'get it now',
        'order now',
        'visit my website',
    ];

    private const MAX_LINKS = 2;
    private const MIN_CONTENT_LENGTH = 2;
    private const MAX_COMMENTS_PER_HOUR = 10;

    public function isSpam(string $content, User $user): bool
    {
        return $this->containsSpamKeywords($content) ||
            $this->hasTooManyLinks($content) ||
            $this->isTooShort($content) ||
            $this->isPostingTooFrequently($user);
    }

    private function containsSpamKeywords(string $content): bool
    {
        $content = strtolower($content);
        foreach (self::SPAM_KEYWORDS as $keyword) {
            if (str_contains($content, $keyword)) {
                return true;
            }
        }
        return false;
    }

    private function hasTooManyLinks(string $content): bool
    {
        $linkCount = substr_count($content, 'http://') +
            substr_count($content, 'https://') +
            substr_count($content, 'www.');
        return $linkCount > self::MAX_LINKS;
    }

    private function isTooShort(string $content): bool
    {
        return str_word_count($content) < self::MIN_CONTENT_LENGTH;
    }

    private function isPostingTooFrequently(User $user): bool
    {
        $recentComments = Comment::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $recentComments >= self::MAX_COMMENTS_PER_HOUR;
    }
}
