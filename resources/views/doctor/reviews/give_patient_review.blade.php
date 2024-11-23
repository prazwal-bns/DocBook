@extends('doctor.doctor_dashboard')
@section('content')

<div class="container px-4 py-8 mx-auto">
    <!-- Title and description -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Give Review for Patient</h1>
        <p class="text-lg text-gray-600">Provide feedback on the completed appointment.</p>
    </div>
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

    <!-- Appointment Information Card -->
    <div class="p-6 mb-8 bg-white rounded-lg shadow-lg hover:bg-blue-50">
        <h2 class="mb-4 text-3xl font-semibold text-gray-800">Appointment Details</h2>

        <!-- Doctor and Patient Information -->
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Doctor Name:</span>
                <span class="font-semibold text-gray-600">{{ $appointment->doctor->user->name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Patient Name:</span>
                <span class="font-semibold text-gray-600">{{ $appointment->patient->user->name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Appointment Date:</span>
                <span class="font-semibold text-gray-600">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, jS F Y ') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Start Time:</span>
                <span class="font-semibold text-gray-600">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">End Time:</span>
                <span class="font-semibold text-gray-600">{{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <div class="p-6 bg-white rounded-lg shadow-lg hover:bg-blue-50">
        <h2 class="mb-4 text-2xl font-semibold text-gray-800">Leave Your Review</h2>

        <form action="{{ route('store.patientReview', $appointment->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="review_msg" class="block mb-2 text-lg font-medium text-gray-700">Your Review</label>
                <textarea id="review_msg" name="review_msg" rows="10" class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Write your review here..." required></textarea>
            </div>

            @if($errors->has('review_msg'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('bio') }}</p>
            @endif

            <div class="flex justify-center mt-6">
                <button type="submit" class="px-6 py-3 font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
