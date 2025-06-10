<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('عرض الخبر') }}
            </h2>
            <a href="{{ route('news.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('العودة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold mb-2">{{ $news->name }}</h1>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <span>{{ $news->created_at->format('Y-m-d H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $news->user->name ?? 'غير معروف' }}</span>
                        </div>

                        <div class="mb-4">
                            @foreach ($news->categories as $category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mr-1">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>

                        <div class="prose dark:prose-invert max-w-none">
                            {!! nl2br(e($news->content)) !!}
                        </div>
                    </div>



                    @if (Auth::check() && (Auth::user()->id === $news->user_id || Auth::user()->role === 'admin'))
<div class="flex space-x-4 rtl:space-x-reverse mt-6">
    <a href="{{ route('news.edit', $news) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        {{ __('تعديل') }}
    </a>

    <form action="{{ route('news.destroy', $news) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('هل أنت متأكد من حذف هذا الخبر؟')">
            {{ __('حذف') }}
        </button>
    </form>
</div>
@endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
