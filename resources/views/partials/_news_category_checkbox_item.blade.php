<ul class="category-list">
    @foreach ($categories as $category)
        <li class="category-item mb-2 {{ in_array($category->id, old('categories', $selectedCategories ?? [])) ? 'selected' : '' }}">
            <div class="flex items-center">
                @if ($category->children->isNotEmpty())
                    <button type="button" class="toggle-children focus:outline-none" data-category-id="{{ $category->id }}">
                        <svg class="text-gray-500 dark:text-gray-400 transition-transform duration-300 transform rotate-0 folder-icon-{{ $category->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <span class="w-6 h-6 mr-2"></span> {{-- Placeholder for alignment --}}
                @endif
                <div class="flex items-center flex-grow">
                    <input id="category-{{ $category->id }}" name="categories[]" type="checkbox" value="{{ $category->id }}" class="text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array($category->id, old('categories', $selectedCategories ?? [])) ? 'checked' : '' }}>
                    <label for="category-{{ $category->id }}" class="mr-2 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $category->name }}</label>
                </div>
            </div>
            @if ($category->children->isNotEmpty())
                <div id="children-{{ $category->id }}" class="children-container overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0px;">
                    @include('partials._news_category_checkbox_item', ['categories' => $category->children, 'selectedCategories' => $selectedCategories])
                </div>
            @endif
        </li>
    @endforeach
</ul>
