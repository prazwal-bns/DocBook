@extends('admin.admin_dashboard')
@section('content')
<div class="flex-1 bg-gray-100">
    <div class="px-6 py-12 sm:px-8">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <!-- Welcome Message -->
            <h2 class="text-3xl font-semibold text-gray-800">Welcome, Admin!</h2>
            <p class="mt-4 text-lg text-gray-600">
                {{ __("You're logged in as Admin. Use the sidebar to navigate through different sections of the dashboard.") }}
            </p>

            <!-- Success Message -->
            @if(session('success'))
                <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
                    <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="flex items-center px-6 py-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-md">
                    <i class="mr-2 text-red-600 fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Total Users -->
                <div class="p-6 text-center bg-blue-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-blue-800">Total Users</h3>
                    <p class="mt-4 text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
                    <p class="mt-2 text-sm text-blue-400">Registered on the platform.</p>
                </div>

                <!-- Total Doctors -->
                <div class="p-6 text-center bg-green-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-green-800">Total Doctors</h3>
                    <p class="mt-4 text-3xl font-bold text-green-600">{{ $totalDoctors }}</p>
                    <p class="mt-2 text-sm text-green-400">Active and verified doctors.</p>
                </div>

                <!-- Total Patients -->
                <div class="p-6 text-center bg-yellow-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-yellow-800">Total Patients</h3>
                    <p class="mt-4 text-3xl font-bold text-yellow-600">{{ $totalPatients }}</p>
                    <p class="mt-2 text-sm text-yellow-800">Patients currently registered.</p>
                </div>

                <!-- Pending Appointments -->
                <div class="p-6 text-center bg-purple-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-purple-800">Pending Appointments</h3>
                    <p class="mt-4 text-3xl font-bold text-purple-600">{{ $pendingAppointments }}</p>
                    <p class="mt-2 text-sm text-purple-400">Appointments in pending progress.</p>
                </div>

                <!-- Completed Appointments -->
                <div class="p-6 text-center bg-green-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-green-800">Completed Appointments</h3>
                    <p class="mt-4 text-3xl font-bold text-green-600">{{ $completedAppointments }}</p>
                    <p class="mt-2 text-sm text-green-400">Appointments that have completed.</p>
                </div>


            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-gray-800">Quick Actions</h3>
                <p class="mt-4 text-lg text-gray-600">Take immediate actions on key tasks:</p>
                <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('view.doctors') }}" class="block p-6 text-center bg-indigo-100 rounded-lg shadow-md hover:bg-indigo-200">
                        <i class="text-3xl text-indigo-600 fas fa-users"></i>
                        <h4 class="mt-4 text-lg font-semibold text-indigo-800">Manage Doctors</h4>
                        <p class="mt-2 text-sm text-indigo-600">Add, edit, or remove doctor accounts.</p>
                    </a>
                    <a href="{{ route('view.patients') }}" class="block p-6 text-center bg-green-100 rounded-lg shadow-md hover:bg-green-200">
                        <i class="text-3xl text-green-600 fas fa-user-md"></i>
                        <h4 class="mt-4 text-lg font-semibold text-green-800">Manage Patients</h4>
                        <p class="mt-2 text-sm text-green-600">Verify and manage patient profiles.</p>
                    </a>
                    <a href="" class="block p-6 text-center bg-yellow-100 rounded-lg shadow-md hover:bg-yellow-200">
                        <i class="text-3xl text-yellow-600 fas fa-calendar-alt"></i>
                        <h4 class="mt-4 text-lg font-semibold text-yellow-800">Check Appointments</h4>
                        <p class="mt-2 text-sm text-yellow-600">View and manage appointments.</p>
                    </a>

                    <a href="" class="block p-6 text-center bg-blue-100 rounded-lg shadow-md hover:bg-blue-200">
                        <i class="text-3xl text-blue-600 fas fa-cogs"></i>
                        <h4 class="mt-4 text-lg font-semibold text-blue-800">Update Settings</h4>
                        <p class="mt-2 text-sm text-blue-600">Manage platform settings.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
