@extends('patient.patient_dashboard')

@section('content')
<div class="container flex-1 px-4 mx-auto mt-10">
    <h1 class="mb-8 text-3xl font-bold text-center text-purple-800">
        Welcome, {{ auth()->user()->name }}!
    </h1>

    <!-- Overview Section -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Pending Appointments Card -->
        <div class="flex items-center p-6 text-white rounded-lg shadow-lg bg-gradient-to-r from-red-500 to-red-800">
            <div>

                <svg class="w-12 h-12 mr-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                  </svg>

            </div>
            <div>
                <h3 class="text-2xl font-semibold">Pending Appointments</h3>
                <p class="text-4xl font-bold">{{ $appointments->where('status', 'pending')->count() }}</p>
            </div>
        </div>

        <!-- Confirmed Appointments Card -->
        <div class="flex items-center p-6 text-white rounded-lg shadow-lg bg-gradient-to-r from-blue-500 to-blue-800">
            <div>
                <svg class="w-12 h-12 mr-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                  </svg>

            </div>
            <div>
                <h3 class="text-2xl font-semibold">Confirmed Appointments</h3>
                <p class="text-4xl font-bold">{{ $appointments->where('status', 'confirmed')->count() }}</p>
            </div>
        </div>

        <!-- Completed Appointments Card -->
        <div class="flex items-center p-6 text-white rounded-lg shadow-lg bg-gradient-to-r from-green-400 to-green-800">
            <div>
                <svg class="w-12 h-12 mr-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-semibold">Completed Appointments</h3>
                <p class="text-4xl font-bold">{{ $appointments->where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="mt-10">
        <div class="flex items-center mb-6 space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-700">
                Quick Actions
            </h2>
        </div>

        <div class="flex flex-col space-y-4 md:flex-row md:space-x-6 md:space-y-0">
            <a href="{{ route('view.my.appointment') }}" class="flex items-center justify-center px-6 py-3 text-white transition-all duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                View Appointments
            </a>
            <a href="{{ route('patient.profile') }}" class="flex items-center justify-center px-6 py-3 text-white transition-all duration-300 bg-teal-600 rounded-lg hover:bg-teal-700">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-width="2"  stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                  </svg>
                Update Profile
            </a>

            <a href="{{ route('make.appointment') }}" class="flex items-center justify-center px-6 py-3 text-white transition-all duration-300 bg-purple-600 rounded-lg hover:bg-teal-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="w-6 h-6 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                </svg>


                View all Specializations
            </a>
        </div>
    </div>


    <!-- Upcoming Appointments -->
    <div class="mt-10">
        <h3 class="mb-6 text-2xl font-bold text-center text-gray-700">Upcoming Appointments</h3>
        @if($appointments->where('status', 'pending')->isEmpty())
            <p class="text-center text-gray-600">No upcoming appointments!</p>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($appointments->where('status', 'pending') as $appointment)
                    <div class="p-6 rounded-lg shadow-lg bg-blue-50">
                        <h4 class="mb-2 text-xl font-bold text-blue-600">Dr. {{ $appointment->doctor->user->name }}</h4>
                        <p class="text-gray-600"><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</p>
                        <p class="text-gray-600"><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i a') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i a') }}</p>
                        <p class="text-gray-600"><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                        <a href="{{ route('view.appointment.details', $appointment->id) }}" class="block px-6 py-3 mt-4 text-white transition-all duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
