@extends('patient.patient_dashboard')

@section('content')
<div class="p-6 mt-12 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-bold text-gray-700">Book an Appointment with Dr. {{ $doctor->user->name }}</h2>

    <!-- Display Success, Error, or Info Messages -->
    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-medium text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm font-medium text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="p-4 mb-4 text-sm font-medium text-blue-700 bg-blue-100 rounded-lg">
            {{ session('info') }}
        </div>
    @endif

    <!-- Appointment Form -->
    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

        <!-- Date Picker -->
        <div class="mb-4">
            <label for="appointment_date" class="block text-sm font-medium text-gray-700">Select Appointment Date</label>
            <input type="date" name="appointment_date" id="appointment_date"
                   class="block w-full mt-2  rounded-lg @error('appointment_date') border-red-500 @enderror"
                   value="{{ old('appointment_date') }}" required>

            @error('appointment_date')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Time Picker -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Start Time -->
            <div class="mb-4">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" name="start_time" id="start_time"
                       class="block w-full mt-2  rounded-lg @error('start_time') border-red-500 @enderror"
                       value="{{ old('start_time') }}" required>

                @error('start_time')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Time -->
            <div class="mb-4">
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" name="end_time" id="end_time"
                       class="block w-full mt-2  rounded-lg @error('end_time') border-red-500 @enderror"
                       value="{{ old('end_time') }}" required>

                @error('end_time')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Book Appointment
            </button>
        </div>
    </form>
</div>
@endsection
