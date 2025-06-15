<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    {{-- <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a> --}}
                </div>

                @if (Auth::check() && Auth::user()->role === 'admin')
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('لوحة التحكم') }}
                    </x-nav-link> --}}
                    <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*') && !request()->routeIs('news.home') && !request()->routeIs('news.view')">
                        {{ __('الأخبار') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*') || request()->routeIs('categories.*')">
                        {{ __('التصنيفات') }}
                    </x-nav-link>
                    <x-nav-link :href="route('news.home')" :active="request()->routeIs('news.home') || request()->routeIs('news.view')">
                        {{ __('الصفحة الرئيسية') }}
                    </x-nav-link>
                </div>
{{-- dddd --}}
@if (Auth::check())
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center my-5 py-1 px-1 rounded-md hover:bg-gray-100">
       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z">
    </path>
</svg>
        {{-- <span class="ml-1">الإشعارات</span> --}}
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="bg-red-500 text-white rounded-full px-2 ml-2 text-sm">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto">
        @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
            <div class="px-4 py-3 border-b hover:bg-gray-50 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50' }}">
                <div class="text-sm">
                    {{ $notification->data['message'] ?? 'إشعار جديد' }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
                @if(!$notification->read_at)
                    <button class="text-xs text-blue-600 hover:text-blue-800 mt-1"
                            onclick="markAsRead('{{ $notification->id }}')">
                        تمييز كمقروء
                    </button>
                @endif
            </div>
        @empty
            <div class="px-4 py-3 text-gray-500 text-center">
                لا توجد إشعارات
            </div>
        @endforelse

        @if(auth()->user()->notifications()->count() > 10)
            <div class="px-4 py-2 text-center">
                <a href="#" class="text-blue-600 text-sm">عرض جميع الإشعارات</a>
            </div>
        @endif
    </div>
</div>
@endif
{{-- ddd --}}

            </div>


            @else
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*') && !request()->routeIs('news.home') && !request()->routeIs('news.view')">
                    {{ __('الأخبار') }}
                </x-nav-link>

                <x-nav-link :href="route('news.home')" :active="request()->routeIs('news.home') || request()->routeIs('news.view')">
                    {{ __('الصفحة الرئيسية') }}
                </x-nav-link>
            </div>
        </div>

        @endif



        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::check() ? Auth::user()->name : 'زائر' }}</div>

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    {{-- <x-dropdown-link :href="route('profile.edit')">
                        {{ __('الملف الشخصي') }}
                    </x-dropdown-link> --}}

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('تسجيل الدخول') }}
                        </x-dropdown-link>


                    </form>
                </x-slot>
            </x-dropdown>
        </div>


    </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('لوحة التحكم') }}
            </x-responsive-nav-link> --}}
            <x-responsive-nav-link :href="route('news.index')" :active="request()->routeIs('news.*') && !request()->routeIs('news.home') && !request()->routeIs('news.view')">
                {{ __('الأخبار') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*') || request()->routeIs('categories.*')">
                {{ __('التصنيفات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('news.home')" :active="request()->routeIs('news.home') || request()->routeIs('news.view')">
                {{ __('الصفحة الرئيسية') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::check() ? Auth::user()->name : 'زائر' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::check() ? Auth::user()->name : 'زائر' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('الملف الشخصي') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>




<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
