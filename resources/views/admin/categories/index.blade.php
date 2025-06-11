<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('إدارة التصنيفات') }}
            </h2>
            <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('إضافة تصنيف جديد') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Modal for adding new category -->
            <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">إضافة تصنيف جديد</h3>
                        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 text-right">
                            @csrf
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('اسم التصنيف')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="description" :value="__('وصف التصنيف')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="parent_id" :value="__('التصنيف الأب')" />
                                <div class="mt-1 space-y-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm p-3 max-h-60 overflow-y-auto">
                                    <div>
                                        <input type="radio" id="parent_category_none" name="parent_id" value="" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ old('parent_id') == '' ? 'checked' : '' }}>
                                        <label for="parent_category_none" class="ml-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">-- بدون تصنيف أب --</label>
                                    </div>
                                    <ul class="space-y-2">
                                        @foreach ($categories as $category)
                                            @include('partials._category_select_item', ['category' => $category, 'selectedCategory' => old('parent_id')])
                                        @endforeach
                                    </ul>
                                </div>
                                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="mr-3">
                                    {{ __('حفظ') }}
                                </x-primary-button>
                                <button type="button" onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('إلغاء') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 text-right text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 text-right text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الاسم</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 text-right text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 text-right text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">التصنيف الأب</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 text-right text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">{{ Str::limit($category->description, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">{{ $category->parent ? $category->parent->name : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.categories.show', $category) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 ml-2">عرض</a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600 ml-2">تعديل</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600" onclick="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 text-center">لا توجد تصنيفات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-category-select');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const childrenContainer = this.closest('li').querySelector('.category-children-select');
                    const folderArrowIcon = this.querySelector('.folder-arrow-icon');

                    if (childrenContainer) {
                        childrenContainer.classList.toggle('hidden');
                        folderArrowIcon.classList.toggle('rotate-90');
                    }
                });
            });
        });
    </script>
</x-app-layout>
