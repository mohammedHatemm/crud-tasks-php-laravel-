<li>
    <div class="flex items-center">
        <input type="radio" id="parent_category_{{ $category->id }}" name="parent_id" value="{{ $category->id }}" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ (old('parent_id') == $category->id || (isset($selectedCategory) && $selectedCategory == $category->id)) ? 'checked' : '' }}>
        <label for="parent_category_{{ $category->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300 flex-1 cursor-pointer">{{ $category->name }}</label>
        @if ($category->children->count() > 0)
            <button type="button" class="toggle-category-select text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none focus:text-gray-700 dark:focus:text-gray-200 ml-2">
                <svg class="w-4 h-4 folder-arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        @endif
    </div>
    @if ($category->children->count() > 0)
        <ul class="mr-6 mt-2 space-y-1 category-children-select hidden">
            @foreach ($category->children as $child)
                @include('partials._category_select_item', ['category' => $child, 'selectedCategory' => (isset($selectedCategory) ? $selectedCategory : null)])
            @endforeach
        </ul>
    @endif
</li>
