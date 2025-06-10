<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $news->name }}</h1>
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('العودة') }}
                        </a>
                    </div>

                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>{{ $news->created_at->format('Y-m-d H:i') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $news->user->name ?? 'غير معروف' }}</span>
                    </div>

                    <div class="mb-6">
                        @foreach ($news->categories as $category)
                        <a href="{{ route('news.home', ['category' => $category->id]) }}" class="inline-flex items-center px-3 py-1 mr-2 mb-2 text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors duration-200">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>

                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">أخبار ذات صلة</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                        $relatedNews = \App\Models\News::whereHas('categories', function($query) use ($news) {
                        $query->whereIn('categories.id', $news->categories->pluck('id'));
                        })
                        ->where('id', '!=', $news->id)
                        ->latest()
                        ->take(3)
                        ->get();
                        @endphp

                        @forelse ($relatedNews as $item)
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <a href="{{ route('news.view', $item) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">
                                        {{ Str::limit($item->name, 50) }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                    {{ Str::limit(strip_tags($item->content), 100) }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->created_at->format('Y-m-d') }}
                                    </span>
                                    <a href="{{ route('news.view', $item) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                        قراءة المزيد
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-3 text-center py-4">
                            <p class="text-gray-500 dark:text-gray-400">لا توجد أخبار ذات صلة.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
