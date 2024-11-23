@extends('patient.patient_dashboard')

@section('content')
<div class="p-6 mt-12 bg-white rounded-lg shadow-md">

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Left: Doctor's Schedule -->
       @if ($doctor->schedules->isNotEmpty())
        <div class="p-4 rounded-lg shadow-md bg-gray-50">
            <h3 class="mb-6 text-xl font-semibold text-gray-800">Dr. {{ $doctor->user->name }}'s Schedule</h3>
            <ul class="space-y-1">
                @foreach ($doctor->schedules as $schedule)
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
        <!-- Right: Appointment Form -->
        <div>
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

            @error('doctor_id')
                <div class="p-4 mb-4 text-sm font-medium text-red-700 bg-red-100 rounded-lg">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex flex-col items-center text-center">
                <!-- Doctor's Profile Image Placeholder -->
                <div class="w-40 h-40 mb-3 overflow-hidden bg-gray-200 rounded-full">
                    <img src="https://static.vecteezy.com/system/resources/previews/015/412/022/non_2x/doctor-round-avatar-medicine-flat-avatar-with-male-doctor-medical-clinic-team-round-icon-medical-collection-illustration-vector.jpg"
                            alt="{{ $doctor->user->name }}"
                            class="object-cover w-full h-full">
                </div>

                <h2 class="text-2xl font-semibold text-gray-700">{{ $doctor->user->name }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ $doctor->user->email }}</p>
                <p class="mt-2 text-sm text-gray-600">Phone No: {{ $doctor->user->phone }}</p>

            </div>

            <!-- Appointment Form -->
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                <!-- Date Picker -->
                <div class="mb-4">
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700">Select Appointment Date</label>
                    <input type="date"
                        name="appointment_date"
                        id="appointment_date"
                        class="block w-full mt-2 rounded-lg @error('appointment_date') border-red-500 @enderror"
                        value="{{ old('appointment_date') }}"
                        min="{{ date('Y-m-d') }}"
                        required>

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
       @else
        <div class="p-6 mt-4 font-semibold text-red-700 bg-blue-50">
            <p>This doctor is not available at the moment.</p>
        </div>
       @endif

    </div>
</div>
@endsection
