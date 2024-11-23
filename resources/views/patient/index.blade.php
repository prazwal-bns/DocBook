@extends('patient.patient_dashboard')

@section('content')
<div class="container flex-1 px-4 mx-auto mt-10">
    <h1 class="mb-8 text-3xl font-bold text-center text-purple-800">Welcome to Your Health Dashboard, {{ auth()->user()->name }}!</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md shadow-md">
            <i class="mr-2 text-green-600 fas fa-check-circle"></i>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    <!-- Error Message -->
    @if($errors->any())
        <div class="flex items-center px-6 py-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-md shadow-md">
            <i class="mr-2 text-red-600 fas fa-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="mb-8 text-2xl font-semibold text-center text-gray-700">Here's a Snapshot of Your Health Journey</h2>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Card 1: Profile Summary -->
        <div class="p-6 transition-all duration-300 ease-in-out bg-white rounded-lg shadow-xl hover:shadow-2xl">
            <h3 class="mb-4 text-xl font-semibold text-blue-600">Your Profile Summary</h3>
            <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="text-gray-600"><strong>Phone:</strong> {{ $user->phone ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Address:</strong> {{ $user->address ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Gender:</strong> {{ ucfirst($patient->gender) ?? 'Not Specified' }}</p>
        </div>

        <!-- Card 2: Appointment Stats -->
        <div class="p-6 transition-all duration-300 ease-in-out bg-white rounded-lg shadow-xl hover:shadow-2xl">
            <h3 class="mb-4 text-xl font-semibold text-blue-600">Your Appointment Summary</h3>
            <p class="text-gray-600"><strong>Total Appointments:</strong> {{ $appointments->count() }}</p>
            <p class="text-gray-600"><strong>Upcoming Appointments:</strong> {{ $appointments->where('status', 'pending')->count() }}</p>
            <p class="text-gray-600"><strong>Completed Appointments:</strong> {{ $appointments->where('status', 'completed')->count() }}</p>
        </div>

        <!-- Card 3: Quick Actions -->
        <div class="p-6 transition-all duration-300 ease-in-out bg-white rounded-lg shadow-xl hover:shadow-2xl">
            <h3 class="mb-4 text-xl font-semibold text-blue-600">Quick Actions</h3>
            <p class="mb-4 text-gray-600">Manage your appointments, update your profile, or view your medical history all in one place.</p>
            <div class="flex space-x-4">
                <a href="{{ route('view.my.appointment') }}" class="w-full px-6 py-3 font-semibold text-white transition-all duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">View Appointments</a>
                <a href="{{ route('patient.profile') }}" class="w-full px-6 py-3 font-semibold text-white transition-all duration-300 bg-teal-600 rounded-lg hover:bg-teal-700">Edit Profile</a>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="mt-10">
        <h3 class="mb-6 text-2xl font-semibold text-center text-gray-700">Your Upcoming Appointments</h3>
        @if($appointments->where('status', 'pending')->isEmpty())
            <p class="text-center text-gray-600">You don't have any upcoming appointments at the moment. Stay healthy!</p>
        @else
            <div class="space-y-6">
                @foreach($appointments->where('status', 'pending') as $appointment)
                    <div class="p-6 mb-4 transition-all duration-300 ease-in-out bg-white rounded-lg shadow-md hover:shadow-xl">
                        <h4 class="text-lg font-semibold text-blue-600">Appointment with Dr. {{ $appointment->doctor->user->name }}</h4>
                        <p class="text-gray-600"><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y, g:i a') }}</p>
                        <p class="text-gray-600"><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                        <a href="{{ route('view.appointment.details', $appointment->id) }}" class="inline-block px-6 py-3 mt-4 text-white transition-all duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">View Details</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
