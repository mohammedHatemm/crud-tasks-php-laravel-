<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <style>
                        .category-children {
                            max-height: 0;
                            overflow: hidden;
                            transition: max-height 0.3s ease-in-out;
                            color: brown;
                        }

                        .category-children.open {
                            max-height: 500px;
                            /* Adjust as needed */
                        }

                        .folder-icon {
                            transition: transform 0.2s ease-in-out;
                        }

                        .folder-icon.open {
                            transform: rotate(90deg);
                        }
                    </style>

                    <!-- تحديث قسم التصنيفات في الـ sidebar -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">التصنيفات</h3>
                        <ul class="space-y-2 category-list">
                            <li>
                                <a href="{{ route('news.home') }}"
                                    class="text-indigo-600 dark:text-indigo-400 hover:underline category-filter {{ !request('category') ? 'font-bold active-filter' : '' }}">
                                    جميع الأخبار
                                </a>
                            </li>
                            @foreach ($categories as $category)
                            @include('partials._category_item', ['category' => $category]) {{-- استدعاء الجزء المتكرر --}}
                            @endforeach
                        </ul>

                        <h3 class="text-lg font-semibold mt-8 mb-4 text-gray-900 dark:text-gray-100">تصفية حسب التاريخ</h3>
                        <ul class="space-y-2 date-filter">
                            <li>
                                <a href="{{ route('news.home', array_merge(request()->except('date'), ['date' => 'today'])) }}" data-date="today" class="text-indigo-600 dark:text-indigo-400 hover:underline {{ request('date') == 'today' ? 'font-bold active-filter' : '' }}">
                                    اليوم
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('news.home', array_merge(request()->except('date'), ['date' => 'week'])) }}" data-date="week" class="text-indigo-600 dark:text-indigo-400 hover:underline {{ request('date') == 'week' ? 'font-bold active-filter' : '' }}">
                                    هذا الأسبوع
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('news.home', array_merge(request()->except('date'), ['date' => 'month'])) }}" data-date="month" class="text-indigo-600 dark:text-indigo-400 hover:underline {{ request('date') == 'month' ? 'font-bold active-filter' : '' }}">
                                    هذا الشهر
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('news.home', array_merge(request()->except('date'))) }}" data-date="" class="text-indigo-600 dark:text-indigo-400 hover:underline {{ !request('date') ? 'font-bold active-filter' : '' }}">
                                    الكل
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="md:col-span-3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <!-- Search Bar -->
                        <div class="mb-6">
                            <form action="{{ route('news.home') }}" method="GET" class="flex search-form">
                                @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if (request('date'))
                                <input type="hidden" name="date" value="{{ request('date') }}">
                                @endif
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن الأخبار..." class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <button type="submit" class="mr-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    بحث
                                </button>
                            </form>
                        </div>

                        <!-- Active Filters -->
                        @if (request('category') || request('search') || request('date'))
                        <div class="mb-6 flex flex-wrap items-center">
                            <span class="text-gray-700 dark:text-gray-300 ml-2">الفلاتر النشطة:</span>
                            @if (request('category'))
                            @php
                            $activeCategory = $categories->first(function($cat) {
                            return $cat->id == request('category');
                            });
                            if (!$activeCategory) {
                            foreach ($categories as $cat) {
                            $found = $cat->children->first(function($child) {
                            return $child->id == request('category');
                            });
                            if ($found) {
                            $activeCategory = $found;
                            break;
                            }
                            }
                            }
                            @endphp
                            @if ($activeCategory)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100 mr-2 mb-2">
                                التصنيف: {{ $activeCategory->name }}
                                <a href="{{ route('news.home', array_merge(request()->except('category'))) }}" class="mr-1 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    &times;
                                </a>
                            </span>
                            @endif
                            @endif
                            @if (request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100 mr-2 mb-2">
                                البحث: {{ request('search') }}
                                <a href="{{ route('news.home', array_merge(request()->except('search'))) }}" class="mr-1 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    &times;
                                </a>
                            </span>
                            @endif
                            @if (request('date'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100 mr-2 mb-2">
                                التاريخ:
                                @if (request('date') == 'today')
                                اليوم
                                @elseif (request('date') == 'week')
                                هذا الأسبوع
                                @elseif (request('date') == 'month')
                                هذا الشهر
                                @endif
                                <a href="{{ route('news.home', array_merge(request()->except('date'))) }}" class="mr-1 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    &times;
                                </a>
                            </span>
                            @endif
                            <a href="{{ route('news.home') }}" id="clear-filters" class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 mb-2">
                                مسح الكل
                            </a>
                        </div>
                        @endif

                        <div id="news-container">
                            @include('partials.news_items')
                        </div>

                        <div class="mt-6">
                            {{ $news->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="{{ asset('js/news-filter.js') }}"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-category');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {

                if (!e.target.closest('a')) {
                    e.preventDefault();

                    const categoryId = this.getAttribute('data-category');
                    const childrenContainer = document.getElementById(`children-${categoryId}`);
                    const folderIcons = this.querySelectorAll('.folder-icon');

                    if (childrenContainer) {
                        childrenContainer.classList.toggle('open');

                        folderIcons.forEach(icon => {
                            icon.classList.toggle('open');
                        });
                    }
                }
            });
        });
    });
</script>
