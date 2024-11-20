<div class="w-64 h-screen overflow-y-auto text-white bg-gray-800">
    <div class="flex items-center justify-center p-6">
        <h1 class="text-2xl font-bold text-yellow-400">Admin Panel</h1>
    </div>
    <nav class="px-6 py-4 space-y-4">
        <!-- Dashboard Link -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center space-x-2 text-gray-300 hover:text-yellow-300 hover:bg-gray-700 p-2 rounded-lg transition-all duration-300
                  {{ request()->routeIs('admin.dashboard') ? 'font-bold text-yellow-400 bg-gray-700' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 3H4a1 1 0 00-1 1v12a1 1 0 001 1h1a1 1 0 001-1V4a1 1 0 00-1-1zM9 4a1 1 0 011 1v11a1 1 0 01-1 1H8a1 1 0 01-1-1V5a1 1 0 011-1h1zM13 5a1 1 0 011 1v10a1 1 0 01-1 1h-1a1 1 0 01-1-1V6a1 1 0 011-1h1z" clip-rule="evenodd" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Manage Patinent Link -->
        <a href="{{ route('view.patients') }}"
           class="flex items-center space-x-2 text-gray-300 hover:text-yellow-300 hover:bg-gray-700 p-2 rounded-lg transition-all duration-300
                  {{ request()->routeIs('view.patients') ? 'font-bold text-yellow-400 bg-gray-700' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 3H4a1 1 0 00-1 1v12a1 1 0 001 1h1a1 1 0 001-1V4a1 1 0 00-1-1zM9 4a1 1 0 011 1v11a1 1 0 01-1 1H8a1 1 0 01-1-1V5a1 1 0 011-1h1zM13 5a1 1 0 011 1v10a1 1 0 01-1 1h-1a1 1 0 01-1-1V6a1 1 0 011-1h1z" clip-rule="evenodd" />
            </svg>
            <span>Manage Patients</span>
        </a>

        <!-- Manage Doctors Link -->
        <a href="{{ route('view.doctors') }}"
           class="flex items-center space-x-2 text-gray-300 hover:text-yellow-300 hover:bg-gray-700 p-2 rounded-lg transition-all duration-300
                  {{ request()->routeIs('view.doctors') ? 'font-bold text-yellow-400 bg-gray-700' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 3H4a1 1 0 00-1 1v12a1 1 0 001 1h1a1 1 0 001-1V4a1 1 0 00-1-1zM9 4a1 1 0 011 1v11a1 1 0 01-1 1H8a1 1 0 01-1-1V5a1 1 0 011-1h1zM13 5a1 1 0 011 1v10a1 1 0 01-1 1h-1a1 1 0 01-1-1V6a1 1 0 011-1h1z" clip-rule="evenodd" />
            </svg>
            <span>Manage Doctors</span>
        </a>

        <!-- Reports Link -->
        <a href="" class="flex items-center space-x-2 text-gray-300 hover:text-yellow-300 hover:bg-gray-700 p-2 rounded-lg transition-all duration-300
                {{ request()->routeIs('admin.users') ? 'font-bold text-yellow-400 bg-gray-700' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 3H4a1 1 0 00-1 1v12a1 1 0 001 1h1a1 1 0 001-1V4a1 1 0 00-1-1zM9 4a1 1 0 011 1v11a1 1 0 01-1 1H8a1 1 0 01-1-1V5a1 1 0 011-1h1zM13 5a1 1 0 011 1v10a1 1 0 01-1 1h-1a1 1 0 01-1-1V6a1 1 0 011-1h1z" clip-rule="evenodd" />
            </svg>
            <span>Manage Users</span>
        </a>

        <!-- Logout Option -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="flex items-center p-2 space-x-2 text-gray-300 transition-all duration-300 rounded-lg hover:text-yellow-300 hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v7m0 0l-3-3m3 3l3-3m5 4v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h12a2 2 0 012 2z"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>

<style>
html, body {
    height: 100%;
}

body {
    display: flex;
    justify-content: flex-start;
}
</style>
