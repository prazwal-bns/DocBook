@extends('patient.patient_dashboard')
@section('content')
<div class="max-w-5xl p-6 mx-auto mt-10 border border-gray-200 shadow-md bg-gradient-to-br from-blue-50 to-white rounded-xl">
    <div class="p-12">
        <h2 class="pb-4 mb-6 text-3xl font-extrabold text-center text-gray-800 border-b-2 border-blue-400">Appointment Details</h2>

        <div class="space-y-6">
            <!-- Doctor Name -->
            <div class="flex items-center justify-between p-4 bg-blue-100 rounded-lg shadow-inner">
                <h3 class="text-xl font-bold text-blue-800">Doctor Name:</h3>
                <span class="ml-4 text-xl font-semibold text-gray-900">{{ $appointment->doctor->user->name }}</span>
            </div>

            <!-- Doctor Contact No -->
            <div class="flex items-center justify-between p-4 bg-blue-100 rounded-lg shadow-inner">
                <h3 class="text-xl font-bold text-blue-800">Doctor Contact No:</h3>
                <span class="ml-4 text-xl font-semibold text-gray-900">{{ ($appointment->doctor->user->phone) ?? 'N/A' }}</span>
            </div>

            <!-- Appointment Date -->
            <div class="flex items-center justify-between p-4 bg-blue-100 rounded-lg shadow-inner">
                <h3 class="text-xl font-bold text-blue-800">Appointment Date:</h3>
                <span class="ml-4 text-xl font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</span>
            </div>

            <!-- Start Time -->
            <div class="flex items-center justify-between p-4 bg-blue-100 rounded-lg shadow-inner">
                <h3 class="text-xl font-bold text-blue-800">Start Time:</h3>
                <span class="ml-4 text-xl font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</span>
            </div>

            <!-- End Time -->
            <div class="flex items-center justify-between p-4 bg-blue-100 rounded-lg shadow-inner">
                <h3 class="text-xl font-bold text-blue-800">End Time:</h3>
                <span class="ml-4 text-xl font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</span>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-between p-4 bg-blue-100 rounded-lg shadow-inner">
                <h3 class="text-xl font-bold text-blue-800">Status:</h3>
                <span class="px-2 py-1 text-xs font-semibold text-white rounded
                    {{ $appointment->status == 'pending' ? 'bg-amber-500' : '' }}
                    {{ $appointment->status == 'confirmed' ? 'bg-green-500' : '' }}
                    {{ $appointment->status == 'cancelled' ? 'bg-red-500' : '' }}
                    {{ $appointment->status == 'completed' ? 'bg-blue-500' : '' }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('view.my.appointment') }}"
               class="px-8 py-3 text-lg font-bold text-white transition-all bg-blue-600 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                Back to Appointments
            </a>
        </div>
    </div>
</div>
@endsection
