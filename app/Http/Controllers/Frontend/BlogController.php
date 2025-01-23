<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'categories', 'tags'])
            ->published()
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

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%");
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

        return view('frontend.blog.index', compact(
            'posts',
            'categories',
            'popular_tags'
        ));
    }

    public function show(string $slug)
    {
        $post = Post::with(['user', 'categories', 'tags', 'comments' => function($query) {
            $query->approved()->whereNull('parent_id');
        }, 'comments.replies' => function($query) {
            $query->approved();
        }])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

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
}
