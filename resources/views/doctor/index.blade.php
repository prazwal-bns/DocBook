@extends('doctor.doctor_dashboard')

@section('content')
<div class="container px-4 mx-auto mt-10">
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
                    class="text-green-800 bg-green-200"
                    {{ old('status', $doctor->status) == 'available' ? 'selected' : '' }}>
                    Available
                </option>

                <option value="not_available"
                    class="text-red-800 bg-red-200"
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

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Profile Summary -->
        <div
            class="p-6 transition-transform transform rounded-lg shadow-md bg-gradient-to-br from-blue-50 to-white hover:scale-105 hover:shadow-lg">
            <h3 class="mb-4 text-xl font-semibold text-blue-700">
                <i class="mr-2 fas fa-user-circle"></i>Profile Summary
            </h3>
            <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="text-gray-600"><strong>Phone:</strong> {{ $user->phone ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Address:</strong> {{ $user->address ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Gender:</strong> {{ ucfirst($doctor->gender) ?? 'Not Specified' }}</p>
            <p class="text-gray-600"><strong>Specialization:</strong> {{ $doctor->specialization->name ?? 'Not
                Specified' }}</p>
        </div>

        <!-- Appointment Stats -->
        <div
            class="p-6 transition-transform transform rounded-lg shadow-md bg-gradient-to-br from-blue-50 to-white hover:scale-105 hover:shadow-lg">
            <h3 class="mb-4 text-xl font-semibold text-blue-700">
                <i class="mr-2 fas fa-calendar-alt"></i>Appointment Stats
            </h3>
            <p class="text-gray-600"><strong>Total Appointments:</strong> {{ $appointments->count() }}</p>
            <p class="text-gray-600"><strong>Upcoming:</strong> {{ $appointments->where('status', 'pending')->count() }}
            </p>
            <p class="text-gray-600"><strong>Completed:</strong> {{ $appointments->where('status', 'completed')->count()
                }}</p>
            <p class="text-gray-600"><strong>Cancelled:</strong> {{ $appointments->where('status', 'cancelled')->count()
                }}</p>
        </div>

        <!-- Schedule Info -->
        <div
            class="p-6 transition-transform transform rounded-lg shadow-md bg-gradient-to-br from-blue-50 to-white hover:scale-105 hover:shadow-lg">
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

    <!-- Tips/Notifications -->
    <div class="p-6 mt-8 rounded-lg shadow-md bg-yellow-50">
        <h3 class="mb-4 text-xl font-semibold text-yellow-700">
            <i class="mr-2 fas fa-lightbulb"></i>Important Reminders
        </h3>
        <ul class="pl-6 space-y-2 text-gray-700 list-disc">
            <li>Check your upcoming appointments daily to stay on track.</li>
            <li>Update your status to inform patients of your availability.</li>
            <li>Ensure your schedules are updated to avoid conflicts.</li>
        </ul>
    </div>
</div>
@endsection
