<li>
  @if ($category->children->count() > 0)
  <!-- التصنيف الذي يحتوي على تصنيفات فرعية -->
  <div class="flex items-center">
    <button class="toggle-category flex items-center text-indigo-600 dark:text-indigo-400 hover:underline category-filter w-full text-right {{ request('category') == $category->id ? 'font-bold active-filter' : '' }}"
      data-category="cat-{{ $category->id }}">
      <!-- أيقونة الفولدر -->
      <svg class="w-5 h-5 ml-2 fill-current folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.8A2 2 0 0 0 7.93 2H4a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2Z" />
        <path d="M12 10v6" />
        <path d="M9 13h6" />
      </svg>
      <a href="{{ route('news.home', ['category' => $category->id]) }}" class="flex-1" data-category-id="{{ $category->id }}">
        {{ $category->name }}
      </a>
      <!-- سهم التوسع -->
      <svg class="w-4 h-4 mr-2 folder-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>
  </div>

  <!-- التصنيفات الفرعية -->
  <ul class="mr-6 mt-2 dark:text-red-500 space-y-1 category-children {{ request('category') && in_array(request('category'), $category->children->pluck('id')->toArray()) ? 'open' : '' }}"
    id="children-cat-{{ $category->id }}">
    @foreach ($category->children as $child)
    @include('partials._category_item', ['category' => $child]) {{-- استدعاء متكرر --}}
    @endforeach
  </ul>
  @else
  <!-- التصنيف الذي لا يحتوي على تصنيفات فرعية -->
  <a href="{{ route('news.home', ['category' => $category->id]) }}"
    data-category-id="{{ $category->id }}"
    class="flex items-center text-indigo-600 dark:text-indigo-400 hover:underline category-filter {{ request('category') == $category->id ? 'font-bold active-filter' : '' }}">
    <svg class="w-4 h-4 ml-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ $category->name }}
  </a>
  @endif
</li>
