<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('عرض التصنيف') }}
            </h2>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('العودة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold mb-2">{{ $category->name }}</h1>

                        {{-- @if ($category->parent)
                        <div class="mb-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">التصنيف الأب:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mr-1">
                                {{ $category->parent->name }}
                            </span>
                        </div>
                        @endif --}}
                        @if ($category->parent)
<div class="mb-4">
    <span class="text-sm text-gray-500 dark:text-gray-400">مسار التصنيف:</span>
    <div class="flex items-center flex-wrap gap-1 mt-1">
        @foreach ($category->getAncestors() as $ancestor)
        <a href="{{ route('admin.categories.show', $ancestor) }}"
           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 hover:bg-blue-200 dark:hover:bg-blue-700 transition">
            {{ $ancestor->name }}
        </a>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        @endforeach

        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
            {{ $category->name }} (الحالي)
        </span>
    </div>
</div>
@endif

                        @if ($category->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">الوصف:</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $category->description }}</p>
                        </div>
                        @endif

                        @if ($category->children->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">التصنيفات الفرعية:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($category->children as $child)
                                <a href="{{ route('admin.categories.show', $child) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100 hover:bg-indigo-200 dark:hover:bg-indigo-700">
                                    {{ $child->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if ($category->news->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">الأخبار المرتبطة:</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($category->news as $newsItem)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <a href="{{ route('news.show', $newsItem) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        {{ $newsItem->name }}
                                    </a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $newsItem->created_at->format('Y-m-d') }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="mb-6">
                            <p class="text-gray-500 dark:text-gray-400">لا توجد أخبار مرتبطة بهذا التصنيف.</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex space-x-4 rtl:space-x-reverse mt-6">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('تعديل') }}
                        </a>

                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">
                                {{ __('حذف') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
