@extends('layouts.app')
@section('title', $user->name . ' - Author Profile')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Author Header -->
            <div class="relative h-32 bg-gradient-to-r from-purple-800 to-red-800"></div>
            <div class="px-6 py-4 md:px-8 relative">
                <div class="flex flex-col md:flex-row items-start md:items-center -mt-16 md:-mt-20">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white overflow-hidden bg-white">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 text-4xl">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <h1 class="text-2xl md:text-3xl font-bold">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ '@' . $user->username }}</p>
                        @if($user->bio)
                            <p class="mt-2 text-gray-700">{{ $user->bio }}</p>
                        @endif
                        <div class="mt-4 flex flex-wrap gap-4">
                            @if($user->website)
                                <a href="{{ $user->website }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    Website
                                </a>
                            @endif
                            @foreach($user->social_links as $platform => $url)
                                @if($url)
                                    <a href="{{ $url }}" target="_blank"
                                       class="text-gray-600 hover:text-gray-800">
                                        {{ ucfirst($platform) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Chart -->
{{--        <div class="mt-8 bg-white rounded-lg shadow p-6">--}}
{{--            <h3 class="text-lg font-semibold mb-4">Post Activity</h3>--}}
{{--            <div class="relative h-64">--}}
{{--                <canvas id="activityChart"></canvas>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Charts Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Activity Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Post Activity</h3>
                <div class="relative h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Category Distribution Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Content Distribution</h3>
                <div class="relative h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Total Posts</div>
                <div class="text-2xl font-bold">{{ $user->author_stats['total_posts'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Total Views</div>
                <div class="text-2xl font-bold">{{ number_format($user->author_stats['total_views']) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Total Comments</div>
                <div class="text-2xl font-bold">{{ number_format($user->author_stats['total_comments']) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Avg. Views per Post</div>
                <div class="text-2xl font-bold">{{ number_format($user->author_stats['avg_views_per_post']) }}</div>
            </div>
        </div>

        <!-- Popular Content -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Most Popular Posts -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Most Popular Content</h3>
                @if($user->author_stats['most_viewed_post'])
                    <div class="mb-4">
                        <div class="text-sm text-gray-500 mb-1">Most Viewed Post</div>
                        <a href="{{ route('blog.show', $user->author_stats['most_viewed_post']->slug) }}"
                           class="text-blue-600 hover:text-blue-800">
                            {{ $user->author_stats['most_viewed_post']->title }}
                            <span class="text-gray-500 text-sm">
                                ({{ number_format($user->author_stats['most_viewed_post']->view_count) }} views)
                            </span>
                        </a>
                    </div>
                @endif
                @if($user->author_stats['most_commented_post'])
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Most Discussed Post</div>
                        <a href="{{ route('blog.show', $user->author_stats['most_commented_post']->slug) }}"
                           class="text-blue-600 hover:text-blue-800">
                            {{ $user->author_stats['most_commented_post']->title }}
                            <span class="text-gray-500 text-sm">
                                ({{ $user->author_stats['most_commented_post']->comments_count }} comments)
                            </span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Favorite Topics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Favorite Topics</h3>
                @if($user->author_stats['favorite_categories']->isNotEmpty())
                    <div class="mb-4">
                        <div class="text-sm text-gray-500 mb-2">Top Categories</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->author_stats['favorite_categories'] as $category)
                                <a href="{{ route('blog.category', $category->slug) }}"
                                   class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-sm">
                                    {{ $category->name }}
                                    <span class="ml-1 text-gray-500">({{ $category->posts_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($user->author_stats['favorite_tags']->isNotEmpty())
                    <div>
                        <div class="text-sm text-gray-500 mb-2">Frequent Tags</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->author_stats['favorite_tags'] as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}"
                                   class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-sm text-blue-600 hover:bg-blue-100">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Author Timeline</h3>
                <div class="text-sm text-gray-500">
                    Member since {{ $user->author_stats['member_since'] }}
                </div>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-500">
                <div>
                    @if($user->author_stats['last_posted'])
                        Last posted {{ $user->author_stats['last_posted']->diffForHumans() }}
                    @else
                        No posts yet
                    @endif
                </div>
                <div>
                    Last active {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                </div>
            </div>
        </div>

        <!-- Author's Posts -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-6">Latest Posts by {{ $user->name }}</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    @include('frontend.partials.post-card', ['post' => $post])
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500">No published posts yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activityData = @json($user->getPostActivityData());
        const ctx = document.getElementById('activityChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: activityData.labels,
                datasets: [
                    {
                        label: 'Posts',
                        data: activityData.posts,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)', // Blue
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Views',
                        data: activityData.views,
                        backgroundColor: 'rgba(16, 185, 129, 0.5)', // Green
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Posts'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Views'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });

    // Initialize Category Distribution Chart
    const categoryData = @json($user->getCategoryDistributionData());
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');

    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: categoryData.labels,
            datasets: [{
                data: categoryData.data,
                backgroundColor: categoryData.colors,
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} posts (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
