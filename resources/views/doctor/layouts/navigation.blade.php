<nav class="p-4 shadow-lg bg-gradient-to-r from-blue-800 to-teal-600">
    <div class="container flex items-center justify-between mx-auto">
        <div class="text-2xl font-semibold text-white">{{ auth()->user()->name }}'s Dashboard</div>
        <div class="flex items-center space-x-6">

            <!-- Home Link -->
            <a href="{{ route('doctor.dashboard') }}"
                class="px-4 py-2 text-white rounded hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300
                      {{ request()->routeIs('doctor.dashboard') ? 'font-bold border-b-4 border-yellow-300' : '' }}
                      transition-all duration-300">
                Home
            </a>

            <!-- Profile Link -->
            <a href="{{ route('doctor.profile') }}"
                class="px-4 py-2 text-white transition-all duration-300 rounded hover:text-yellow-300 {{ request()->routeIs('doctor.profile') ? 'font-bold border-b-4 border-yellow-300' : '' }} hover:border-b-2 hover:border-yellow-300">
                Profile
            </a>

            <!-- Schedule Link -->
            <a href="{{ route('view.schedule') }}"
                class="px-4 py-2 text-white transition-all duration-300 rounded hover:text-yellow-300
                {{ request()->routeIs('view.schedule', 'edit.schedule', 'add.schedule') ? 'font-bold border-b-4 border-yellow-300' : '' }} hover:border-b-2 hover:border-yellow-300">
                My Schedule
            </a>


            <!-- Appointments Link -->
            <a href="{{ route('view.doctor.appointments') }}"
            class="px-4 py-2 text-white transition-all duration-300 rounded hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300
            {{ request()->routeIs('view.doctor.appointments', 'edit.patient.appointment', 'view.a.doctor.appointment', 'give.patient.review', 'view.patient.review', 'edit.status') ? 'font-bold border-b-4 border-yellow-300' : '' }}">
             My Appointments
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
