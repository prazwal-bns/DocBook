@extends('doctor.doctor_dashboard')
@section('content')
    @if ($appointment->review()->doesntExist())
        <div class="p-6 mt-4 font-semibold text-red-700 bg-blue-50">
            <p>You haven't given any reviews yet.</p>
        </div>
    @else
    <div class="max-w-4xl p-8 mx-auto mt-6 bg-white rounded-lg shadow-lg">
        <h2 class="mb-6 text-2xl font-semibold text-gray-800">Your Review for Appointment #{{ $appointment->id }}</h2>

        <!-- Appointment Details Section -->
        <div class="space-y-6">
            <div class="p-6 rounded-lg shadow-sm bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Appointment Details</h3>
                <div class="mt-4 text-gray-600">
                    <p><strong>Patient Name:</strong> {{ $appointment->patient->user->name }}</p>
                    <p><strong>Doctor Name:</strong> {{ $appointment->doctor->user->name }}</p>
                    <p><strong>Appointment Date:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y, g:i a') }}</p>
                    <p><strong>Start Time:</strong> {{ $appointment->start_time }}</p>
                    <p><strong>End Time:</strong> {{ $appointment->end_time }}</p>
                </div>
            </div>

            <!-- Review Section -->
            <div class="p-6 rounded-lg shadow-sm bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Your Review</h3>
                <div class="mt-4">
                    <p><strong>Review Message:</strong></p>
                    <p class="mt-2 text-gray-800">{{ $appointment->review->review_msg }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('view.doctor.appointments') }}" class="inline-block px-6 py-3 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Back to Appointments
            </a>
        </div>
    </div>
    @endif
@endsection
