<div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end z-10">
    @auth
        <a href="{{ route('redirectUser',[auth()->user()->user_type]) }}"
           class="font-semibold text-gray-500 hover:text-gray-50 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
    @else
        <a href="{{ route('login') }}"
           class="font-semibold text-gray-500 hover:text-gray-50 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
           wire:navigate>Log in</a>

    @endauth
</div>
