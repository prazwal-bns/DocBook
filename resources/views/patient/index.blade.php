@extends('patient.patient_dashboard')

@section('content')
<div class="container flex-1 px-4 mx-auto mt-10">
    <h1 class="mb-8 text-3xl font-bold text-center text-red-800">Welcome to Your Dashboard: {{ auth()->user()->name }}</h1>

    <!-- Display success message -->
<!-- Display success message with icon -->
    @if(session('success'))
        <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
            <!-- Success icon -->
            <i class="mr-2 text-green-600 fas fa-check-circle"></i>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    <!-- Display error message with icon -->
    @if($errors->any())
        <div class="flex items-center px-6 py-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-md">
            <!-- Error icon -->
            <i class="mr-2 text-red-600 fas fa-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="mb-8 text-2xl font-bold text-center text-gray-700">Having a Good Day, {{ auth()->user()->name }} ??</h1>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Card 1: Profile Summary -->
        <div class="p-6 bg-white rounded-lg shadow-lg hover:shadow-2xl">
            <h3 class="mb-2 text-xl font-semibold">Profile Summary</h3>
            <p class="text-gray-600">Email: {{ $user->email }}</p>
            <p class="text-gray-600">Phone: {{ $user->phone ?? 'Not Provided' }}</p>
            <p class="text-gray-600">Address: {{ $user->address ?? 'Not Provided' }}</p>
            <p class="text-gray-600">Gender: {{ ucfirst($patient->gender) ?? 'Not Specified' }}</p>
        </div>

        <!-- Card 2: Appointment Stats -->
        <div class="p-6 bg-white rounded-lg shadow-lg hover:shadow-2xl">
            <h3 class="mb-2 text-xl font-semibold">Appointment Summary</h3>
            <p class="text-gray-600">Total Appointments: {{ $appointments->count() }}</p>
            <p class="text-gray-600">Upcoming: {{ $appointments->where('status', 'pending')->count() }}</p>
            <p class="text-gray-600">Completed: {{ $appointments->where('status', 'completed')->count() }}</p>
        </div>

        <!-- Card 3: Other Info -->
        <div class="p-6 bg-white rounded-lg shadow-lg hover:shadow-2xl">
            <h3 class="mb-2 text-xl font-semibold">Other Info</h3>
            <p class="text-gray-600">Info:</p>
            <p class="text-gray-600">Info: </p>
            <p class="text-gray-600">Info: </p>
        </div>

    </div>

</div>

@endsection
