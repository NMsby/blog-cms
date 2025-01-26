@extends('layouts.admin')
@section('title', 'Edit Post')
@section('header', 'Edit Post')

@section('content')
    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <x-input-label for="title" value="Title" />
                <x-text-input id="title" type="text" name="title" :value="old('title', $post->title)" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="content" value="Content" />
                <textarea id="content" name="content" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content', $post->content) }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="excerpt" value="Excerpt" />
                <textarea id="excerpt" name="excerpt" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('excerpt', $post->excerpt) }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <x-input-label for="category_ids" value="Categories" />
                    <select id="category_ids" name="category_ids[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" size="4">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', $post->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Hold Ctrl (Windows) or Command (Mac) to select multiple categories</p>
                    <x-input-error :messages="$errors->get('category_ids')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="tag_ids" value="Tags" />
                    <select id="tag_ids" name="tag_ids[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" size="4">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Hold Ctrl (Windows) or Command (Mac) to select multiple tags</p>
                    <x-input-error :messages="$errors->get('tag_ids')" class="mt-2" />
                </div>
            </div>

            <div class="mb-4">
                <x-input-label for="featured_image" value="Featured Image" />
                @if($post->featured_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="h-32 w-auto">
                    </div>
                @endif
                <input type="file" id="featured_image" name="featured_image" class="block mt-1" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="meta_title" value="Meta Title" />
                <x-text-input id="meta_title" type="text" name="meta_title" :value="old('meta_title', $post->meta_title)" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="meta_description" value="Meta Description" />
                <textarea id="meta_description" name="meta_description" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('meta_description', $post->meta_description) }}</textarea>
                <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center justify-end">
                <x-secondary-button onclick="window.history.back()" class="mr-3">
                    Cancel
                </x-secondary-button>
                <x-primary-button>
                    Update Post
                </x-primary-button>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            tinymce.init({
                selector: '#content',
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                    'forecolor backcolor emoticons | help',
                menu: {
                    favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
                },
                menubar: 'favs file edit view insert format tools table help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                height: 500,
                automatic_uploads: true,
                images_upload_url: '{{ route('admin.upload.image') }}',
                images_upload_handler: function (blobInfo, success, failure) {
                    let xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('admin.upload.image') }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.onload = function() {
                        let json;
                        if (xhr.status !== 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };

                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    xhr.send(formData);
                }
            });
        </script>
    @endpush

@endsection
