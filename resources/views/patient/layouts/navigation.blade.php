<nav class="p-4 shadow-lg bg-gradient-to-r from-blue-800 to-teal-600">
    <div class="container flex items-center justify-between mx-auto">
        <div class="text-2xl font-semibold text-white">Patient Dashboard</div>
        <div class="flex items-center space-x-6">

            <!-- Home Link -->
            <a href="{{ route('patient.dashboard') }}"
                class="px-4 py-2 text-white rounded hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300
                      {{ request()->routeIs('patient.dashboard') ? 'font-bold border-b-4 border-yellow-300' : '' }}
                      transition-all duration-300">
                Home
            </a>

            <!-- Profile Link -->
            <a href="{{ route('patient.profile') }}"
                class="px-4 py-2 text-white transition-all duration-300 rounded hover:text-yellow-300 {{ request()->routeIs('patient.profile') ? 'font-bold border-b-4 border-yellow-300' : '' }} hover:border-b-2 hover:border-yellow-300">
                Profile
            </a>

            <!-- Appointments Link -->
            <a href="#"
                class="px-4 py-2 text-white transition-all duration-300 rounded hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300">
                Make Appointment
            </a>

            <!-- Logout Form -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 text-white transition-all duration-300 rounded hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>