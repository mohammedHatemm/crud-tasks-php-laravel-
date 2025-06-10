@if ($news->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($news as $item)
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="p-5">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <a href="{{ route('news.view', $item) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">
                        {{ Str::limit($item->name, 50) }}
                    </a>
                </h3>
            </div>

            <div class="flex flex-wrap mb-3">
                @foreach ($item->categories as $category)
                <a href="{{ route('news.home', ['category' => $category->id]) }}" class="inline-flex items-center px-2 py-1 mr-2 mb-2 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors duration-200">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>

            <p class="text-gray-600 dark:text-gray-300 mb-4">
                {{ Str::limit(strip_tags($item->content), 150) }}
            </p>

            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $item->created_at->format('Y-m-d') }}
                </span>
                <a href="{{ route('news.view', $item) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                    قراءة المزيد
                    <svg class="w-4 h-4 mr-1 rtl:rotate-180" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-10">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">لا توجد أخبار</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لم يتم العثور على أي أخبار تطابق معايير البحث الخاصة بك.</p>
    <div class="mt-6">
        <a href="{{ route('news.home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            عرض جميع الأخبار
        </a>
    </div>
</div>
@endif
