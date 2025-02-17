@extends('layouts.app')
@section('title', 'Create Post')
@section('header', 'Create Post')

@section('content')
    <form action="{{ route('authorposts.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <x-input-label for="title" value="Title" />
                <x-text-input id="title" type="text" name="title" :value="old('title')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="content" value="Content" />
                <textarea id="content" name="content" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="excerpt" value="Excerpt" />
                <textarea id="excerpt" name="excerpt" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('excerpt') }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Categories Selection -->
                <div class="mb-4" x-data="{
                    search: '',
                    selectedCategories: [],
                    categories: [],
                    showDropdown: false,
                    init() {
                        this.categories = JSON.parse(this.$refs.categoriesData.value);
                        this.selectedCategories = JSON.parse(this.$refs.selectedCategoriesData.value);
                    },
                    toggleCategory(id) {
                        const index = this.selectedCategories.indexOf(id);
                        if (index === -1) {
                            this.selectedCategories.push(id);
                        } else {
                            this.selectedCategories.splice(index, 1);
                        }
                    },
                    get filteredCategories() {
                        if (!this.search) return this.categories;
                        return this.categories.filter(category =>
                            category.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                }">
                    <input type="hidden" x-ref="categoriesData" :value='@json($categories)'>
                    <input type="hidden" x-ref="selectedCategoriesData"
                           :value='@json(isset($post) ? old("category_ids", $post->categories->pluck("id")->toArray()) : old("category_ids", []))'>

                    <x-input-label for="category_search" value="Categories" />
                    <div class="relative">
                        <input type="text"
                               id="category_search"
                               x-model="search"
                               @focus="showDropdown = true"
                               @click.away="showDropdown = false"
                               placeholder="Search categories..."
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mb-2">

                        <!-- Dropdown for filtered categories -->
                        <div x-show="showDropdown"
                             class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto">
                            <template x-for="category in filteredCategories" :key="category.id">
                                <div class="p-2 hover:bg-gray-50 cursor-pointer flex items-center space-x-2"
                                     @click="toggleCategory(category.id); search = ''">
                                    <input type="checkbox"
                                           :checked="selectedCategories.includes(category.id)"
                                           :id="'category-' + category.id"
                                           name="category_ids[]"
                                           :value="category.id"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label :for="'category-' + category.id"
                                           x-text="category.name"
                                           class="flex-grow cursor-pointer"></label>
                                </div>
                            </template>
                            <div x-show="filteredCategories.length === 0"
                                 class="p-2 text-gray-500 text-sm">
                                No categories found
                            </div>
                        </div>
                    </div>

                    <!-- Selected Categories -->
                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="id in selectedCategories" :key="id">
                            <div class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-sm flex items-center">
                                <span x-text="categories.find(c => c.id === id).name"></span>
                                <button type="button" @click="toggleCategory(id)" class="ml-1 text-indigo-500 hover:text-indigo-700">×</button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tags Selection -->
                <div class="mb-4" x-data="{
                    search: '',
                    selectedTags: [],
                    tags: [],
                    showDropdown: false,
                    init() {
                        this.tags = JSON.parse(this.$refs.tagsData.value);
                        this.selectedTags = JSON.parse(this.$refs.selectedTagsData.value);
                    },
                    toggleTag(id) {
                        const index = this.selectedTags.indexOf(id);
                        if (index === -1) {
                            this.selectedTags.push(id);
                        } else {
                            this.selectedTags.splice(index, 1);
                        }
                    },
                    get filteredTags() {
                        if (!this.search) return this.tags;
                        return this.tags.filter(tag =>
                            tag.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                }">
                    <input type="hidden" x-ref="tagsData" :value='@json($tags)'>
                    <input type="hidden" x-ref="selectedTagsData"
                           :value='@json(isset($post) ? old("tag_ids", $post->tags->pluck("id")->toArray()) : old("tag_ids", []))'>

                    <x-input-label for="tag_search" value="Tags" />
                    <div class="relative">
                        <input type="text"
                               id="tag_search"
                               x-model="search"
                               @focus="showDropdown = true"
                               @click.away="showDropdown = false"
                               placeholder="Search tags..."
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mb-2">

                        <!-- Dropdown for filtered tags -->
                        <div x-show="showDropdown"
                             class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto">
                            <template x-for="tag in filteredTags" :key="tag.id">
                                <div class="p-2 hover:bg-gray-50 cursor-pointer flex items-center space-x-2"
                                     @click="toggleTag(tag.id); search = ''">
                                    <input type="checkbox"
                                           :checked="selectedTags.includes(tag.id)"
                                           :id="'tag-' + tag.id"
                                           name="tag_ids[]"
                                           :value="tag.id"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label :for="'tag-' + tag.id"
                                           x-text="tag.name"
                                           class="flex-grow cursor-pointer"></label>
                                </div>
                            </template>
                            <div x-show="filteredTags.length === 0"
                                 class="p-2 text-gray-500 text-sm">
                                No tags found
                            </div>
                        </div>
                    </div>

                    <!-- Selected Tags -->
                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="id in selectedTags" :key="id">
                            <div class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-sm flex items-center">
                                <span x-text="tags.find(t => t.id === id).name"></span>
                                <button type="button" @click="toggleTag(id)" class="ml-1 text-indigo-500 hover:text-indigo-700">×</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-input-label for="featured_image" value="Featured Image" />
                <input type="file" id="featured_image" name="featured_image" class="block mt-1" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button onclick="window.history.back()">Cancel</x-secondary-button>
                <x-primary-button>Create Post</x-primary-button>
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
