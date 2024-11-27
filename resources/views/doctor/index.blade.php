@extends('doctor.doctor_dashboard')

@section('content')
<div class="container px-4 mx-auto mt-10">
    <!-- Success Message -->
    @if(session('success'))
        <div class="flex items-center px-6 py-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-md shadow-lg">
            <i class="mr-3 text-xl text-green-600 fas fa-check-circle"></i>
            <div>
                <strong class="text-lg font-semibold">Success!</strong>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if($errors->any())
        <div class="flex items-center px-6 py-4 mb-6 text-red-800 bg-red-100 border border-red-300 rounded-md shadow-lg">
            <i class="mr-3 text-xl text-red-600 fas fa-exclamation-circle"></i>
            <div>
                <strong class="text-lg font-semibold">Oops! Something went wrong.</strong>
                <ul class="mt-2 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <h1 class="mb-8 text-3xl font-bold text-center text-blue-800">Welcome, Dr. {{ auth()->user()->name }}</h1>

    <!-- Status Update -->
    <div class="flex items-center justify-center mb-8 space-x-4">
        @if($doctor->schedules->isNotEmpty())
        <form action="{{ route('setup.status', auth()->user()->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="doctor-status" class="mr-2 text-lg font-semibold text-gray-700">Update Your Status:</label>
            <select id="doctor-status" name="status"
                class="py-2 pl-4 pr-10 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="available"
                    {{ old('status', $doctor->status) == 'available' ? 'selected' : '' }}>
                    Available
                </option>

                <option value="not_available"
                    {{ old('status', $doctor->status) == 'not_available' ? 'selected' : '' }}>
                    On Leave/Unavailable
                </option>
            </select>

            <button type="submit"
                class="px-6 py-2 ml-4 font-semibold text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Update
            </button>

            @error('status')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </form>

        <div class="mb-4">
            <span for="current-status" class="block text-lg font-semibold text-gray-700">Current Status:</span>
            <span id="current-status" class="text-xl font-bold
                {{ old('status', $doctor->status) == 'available' ? 'text-green-600' : 'text-red-600' }}">
                {{ old('status', $doctor->status) == 'available' ? 'Available' : 'On Leave/Unavailable' }}
            </span>
        </div>

        @else
        <div class="p-6 mt-4 font-semibold text-red-700 bg-blue-50">
            <p>You have to set up your schedule to be able to attend patients.</p>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
        <a href="{{ route('view.doctor.appointments') }}" class="p-6 text-white transition-transform transform bg-purple-600 rounded-lg shadow-lg hover:scale-105 hover:shadow-xl">
            <i class="mb-4 text-3xl fas fa-calendar-check"></i>
            <h3 class="text-2xl font-semibold">Manage Appointments</h3>
        </a>

        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-r from-red-500 to-red-800">
            <h3 class="text-2xl font-semibold">Pending Appointments</h3>
            <p class="text-4xl font-bold">{{ $appointments->where('status','pending')->count() }}</p>
        </div>

        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-r from-blue-500 to-blue-800">
            <h3 class="text-2xl font-semibold">Confirmed Appointments</h3>
            <p class="text-4xl font-bold">{{ $appointments->where('status','confirmed')->count() }}</p>
        </div>

        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-r from-green-500 to-green-800">
            <h3 class="text-2xl font-semibold">Completed Appointments</h3>
            <p class="text-4xl font-bold">{{ $appointments->where('status','completed')->count() }}</p>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Profile Summary -->
        <div class="p-6 transition-transform transform rounded-lg shadow-md bg-gradient-to-br from-blue-50 to-white hover:scale-105 hover:shadow-lg">
            <h3 class="mb-4 text-xl font-semibold text-blue-700">
                <i class="mr-2 fas fa-user-circle"></i>Profile Summary
            </h3>
            <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="text-gray-600"><strong>Phone:</strong> {{ $user->phone ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Address:</strong> {{ $user->address ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Gender:</strong> {{ ucfirst($doctor->gender) ?? 'Not Specified' }}</p>
            <p class="text-gray-600"><strong>Specialization:</strong> {{ $doctor->specialization->name ?? 'Not Specified' }}</p>
        </div>

        <!-- Appointment Stats -->
        <div class="p-6 transition-transform transform rounded-lg shadow-md bg-gradient-to-br from-blue-50 to-white hover:scale-105 hover:shadow-lg">
            <h3 class="mb-4 text-xl font-semibold text-blue-700">
                <i class="mr-2 fas fa-calendar-alt"></i>Appointment Stats
            </h3>
            <p class="text-gray-600"><strong>Total Appointments:</strong> {{ $appointments->count() }}</p>
            <p class="text-gray-600"><strong>Upcoming:</strong> {{ $appointments->where('status', 'pending')->count() }}</p>
            <p class="text-gray-600"><strong>Confirmed:</strong> {{ $appointments->where('status', 'confirmed')->count() }}</p>
            <p class="text-gray-600"><strong>Completed:</strong> {{ $appointments->where('status', 'completed')->count() }}</p>
        </div>

        <!-- Schedule Info -->
        <div class="p-6 transition-transform transform rounded-lg shadow-md bg-gradient-to-br from-blue-50 to-white hover:scale-105 hover:shadow-lg">
            <h3 class="mb-4 text-xl font-semibold text-blue-700">
                <i class="mr-2 fas fa-clock"></i>Your Schedule
            </h3>
            @if ($doctor->schedules->isNotEmpty())
                <a href="{{ route('view.schedule') }}"
                    class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    View My Schedules
                </a>
            @else
                <div class="mt-4 text-red-600">
                    <p>You haven't set any schedules yet.</p>
                    <a href="{{ route('add.schedule') }}"
                        class="inline-block px-6 py-2 mt-4 text-center text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Set My Schedule
                    </a>
                </div>
            @endif
        </div>
    </div>
     <!-- Upcoming Appointments -->
     <div class="mt-10">
        <h3 class="mb-6 text-2xl font-bold text-center text-gray-700">Upcoming/Pending Appointments</h3>
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
