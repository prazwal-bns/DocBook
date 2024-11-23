@extends('patient.patient_dashboard')

@section('content')

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

<div class="p-6 mt-12 bg-white rounded-lg shadow-md">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Left: Doctor's Schedule -->
        @if ($appointment->doctor->schedules->isNotEmpty())
            <div class="p-4 rounded-lg shadow-md bg-gray-50">
                <h3 class="mb-6 text-xl font-semibold text-gray-800">Dr. {{ $appointment->doctor->user->name }}'s Schedule</h3>
                <ul class="space-y-1">
                    @foreach ($appointment->doctor->schedules as $schedule)
                        <li class="flex items-center p-4 transition duration-200 bg-white rounded-lg shadow-sm hover:bg-gray-100">
                            <div class="flex-1">
                                <strong class="text-lg font-medium text-gray-700">{{ ucfirst($schedule->day) }}:</strong>
                                <div class="text-gray-600">
                                    <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                @if ($schedule->status === 'available')
                                    <span class="flex items-center ml-3 font-semibold text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Available
                                    </span>
                                @else
                                    <span class="flex items-center ml-3 font-semibold text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Not Available
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="p-6 font-semibold text-red-700 bg-blue-50">
                <p>No schedule available for this doctor at the moment.</p>
            </div>
        @endif

        <!-- Right: Edit Appointment Form -->
        <div class="w-full max-w-md">
            <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Edit Your Appointment with Dr. {{ $appointment->doctor->user->name }}</h2>

            <form action="{{ route('update.my.appointment', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Hidden field to store the doctor ID -->
                <input type="hidden" name="doctor_id" value="{{ $appointment->doctor_id }}">

                <!-- Date Picker -->
                <div class="mb-6">
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700">Select New Appointment Date</label>
                    <input type="date"
                        min="{{ date('Y-m-d') }}"
                        name="appointment_date"
                        id="appointment_date"
                        class="block w-full mt-2 p-3 rounded-lg border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('appointment_date') border-red-500 @enderror"
                        value="{{ old('appointment_date', $appointment->appointment_date) }}" required>

                    @error('appointment_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Picker -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="start_time"
                            class="block w-full mt-2 p-3 rounded-lg border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-500 @enderror"
                            value="{{ old('start_time', $appointment->start_time) }}" required>

                        @error('start_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="end_time"
                            class="block w-full mt-2 p-3 rounded-lg border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-500 @enderror"
                            value="{{ old('end_time', $appointment->end_time) }}" required>

                        @error('end_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-center">
                    <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
