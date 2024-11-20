@extends('admin.admin_dashboard')
@section('content')
<div class="flex-1 bg-gray-100">
    <div class="px-6 py-12 sm:px-8">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-gray-800">Welcome, Admin!</h2>
            <p class="mt-4 text-lg text-gray-600">
                {{ __("You're logged in as Admin. Use the sidebar to navigate through different sections of the dashboard.") }}
            </p>

            <!-- Display success message -->
            @if(session('success'))
                <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
                    <!-- Success icon -->
                    <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <!-- Display error message with icon -->
            @if($errors->any())
                <div class="flex items-center px-6 py-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-md">
                    <!-- Error icon -->
                    <i class="mr-2 text-red-600 fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Card Content with Additional Sections -->
            <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Stats Card 1 -->
                <div class="p-6 text-center bg-blue-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-blue-800">Total Users</h3>
                    <p class="mt-4 text-3xl font-bold text-blue-600">1,520</p>
                    <p class="mt-2 text-sm text-blue-400">Number of users registered on the platform.</p>
                </div>

                <!-- Stats Card 2 -->
                <div class="p-6 text-center bg-green-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-green-800">Active Appointments</h3>
                    <p class="mt-4 text-3xl font-bold text-green-600">215</p>
                    <p class="mt-2 text-sm text-green-400">Number of active appointments in progress.</p>
                </div>

                <!-- Stats Card 3 -->
                <div class="p-6 text-center bg-yellow-100 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-yellow-800">Pending Reports</h3>
                    <p class="mt-4 text-3xl font-bold text-yellow-600">35</p>
                    <p class="mt-2 text-sm text-yellow-400">Reports waiting for approval or review.</p>
                </div>
            </div>

            <!-- More Information -->
            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-gray-800">Quick Actions</h3>
                <p class="mt-4 text-lg text-gray-600">Here are some quick actions you can take:</p>
                <ul class="pl-6 mt-4 list-disc">
                    <li>Manage Users</li>
                    <li>View Reports</li>
                    <li>Check Appointments</li>
                    <li>Update Settings</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
