<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->latest('published_at');

        // Apply filters
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->has('author')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('username', $request->author);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%");
                    });
            });
        }

        $posts = $query->paginate(12)
            ->withQueryString();

        // Sidebar data
        $categories = Category::withCount('posts')
            ->whereHas('posts', function($query) {
                $query->published();
            })
            ->visible()
            ->get();

        $popular_tags = Tag::withCount('posts')
            ->whereHas('posts', function($query) {
                $query->published();
            })
            ->orderByDesc('posts_count')
            ->take(15)
            ->get();

        $popular_authors = User::withCount(['posts' => function($query) {
            $query->published();
        }])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->take(10)
            ->get();

        return view('frontend.blog.index', compact(
            'posts',
            'categories',
            'popular_tags',
            'popular_authors'
        ));
    }

    public function show(Post $post)
    {
        if (!$post->where('status', 'published')) {
            abort(403, "The post is not published.");
        }

        $post->load(['user', 'categories', 'tags', 'comments' => function($query) {
            $query->approved()->whereNull('parent_id');
        }, 'comments.replies' => function($query) {
            $query->approved();
        }]);

        // Increment view count
        $post->increment('view_count');

        // Get related posts
        $related_posts = Post::with(['user', 'categories'])
            ->published()
            ->whereHas('categories', function($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.blog.show', compact('post', 'related_posts'));
    }

    public function author(User $user)
    {
        $posts = Post::with(['categories', 'tags'])
            ->published()
            ->where('user_id', $user->id)
            ->latest('published_at')
            ->paginate(12);

        return view('frontend.blog.author', compact('user', 'posts'));
    }

    public function category(Category $category)
    {
        $posts = Post::with(['user', 'categories'])
            ->published()
            ->whereHas('categories', function($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->latest('published_at')
            ->paginate(12);

        return view('frontend.blog.category', compact('category', 'posts'));
    }

    public function tag(Tag $tag)
    {
        $posts = Post::with(['user', 'categories'])
            ->published()
            ->whereHas('tags', function($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->latest('published_at')
            ->paginate(12);

        return view('frontend.blog.tag', compact('tag', 'posts'));
    }

    public function searchSuggestions(Request $request)
    {
        if (!$request->has('q') || strlen($request->q) < 2) {
            return response()->json([
                'posts' => [],
                'authors' => []
            ]);
        }

        $query = $request->q;

        $posts = Post::published()
            ->where('title', 'like', "%$query%")
            ->take(5)
            ->get()
        ->map(function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'author' => $post->user->name,
            ];
        });

        $authors = User::where(function($q) use ($query) {
            $q->where('name', 'like', "%$query%")
                ->orWhere('username', 'like', "%$query%");
        })
            ->withCount(['posts' => function($q) {
                $q->published();
            }])
            ->having('posts_count', '>', 0)
            ->take(3)
            ->get()
            ->map(function($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'username' => $author->username,
                    'avatar' => $author->avatar ? asset('storage/' . $author->avatar) : null,
                    'posts_count' => $author->posts_count
                ];
            });

        return response()->json([
            'posts' => $posts,
            'authors' => $authors
        ]);
    }
}
