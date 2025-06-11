<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('إضافة خبر جديد') }}
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
                    <form method="POST" action="{{ route('news.store') }}">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('عنوان الخبر')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <x-input-label for="content" :value="__('محتوى الخبر')" />
                            <textarea id="content" name="content" rows="10" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <x-input-label :value="__('التصنيفات')" class="mb-2" />
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <div class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    اختر التصنيفات المناسبة للخبر
                                </div>
                                @include('partials._news_category_checkbox_item', ['categories' => $categories, 'selectedCategories' => old('categories', [])])
                            </div>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="mr-3">
                                {{ __('حفظ') }}
                            </x-primary-button>
                            <a href="{{ route('news.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
