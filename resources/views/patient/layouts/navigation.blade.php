<nav class="p-4 shadow-lg bg-gradient-to-r from-gray-900 to-black">
    <div class="container flex items-center justify-between mx-auto">
        <div class="text-2xl font-semibold text-white">{{ auth()->user()->name }}'s Dashboard</div>
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
            <a href="{{ route('make.appointment') }}"
                class="px-4 py-2  text-white transition-all duration-300 rounded {{ request()->routeIs('make.appointment','view.doctorsBySpecialization','book.appointment') ? 'font-bold border-b-4 border-yellow-300' : '' }} hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300">
                Make Appointment
            </a>

            <!-- Appointments Link -->
            <a href="{{ route('view.my.appointment') }}"
                class="px-4 py-2 text-white transition-all duration-300 rounded {{ request()->routeIs('view.my.appointment', 'view.appointment.details', 'edit.myAppoinment.date','view.doctor.review') ? 'font-bold border-b-4 border-yellow-300' : '' }} hover:text-yellow-300 hover:border-b-2 hover:border-yellow-300">
                View My Appointments
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
