@extends('patient.patient_dashboard')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md ">
    <h2 class="mb-4 text-xl font-bold text-gray-700">{{ Auth::user()->name }}'s' Appointments</h2>
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
    @if($appointmentData->isEmpty())
        <p class="text-gray-600">You have no appointments at the moment.</p>
    @else
        <table class="w-full border-collapse table-auto">
            <thead>
                <tr class="bg-blue-100 border-b">
                    <th class="px-4 py-2 text-left">Doctor Name</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Day</th>
                    <th class="px-4 py-2 text-left">Start Time</th>
                    <th class="px-4 py-2 text-left">End Time</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointmentData as $appointment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded
                                {{ $appointment->status == 'pending' ? 'bg-amber-500' : '' }}
                                {{ $appointment->status == 'confirmed' ? 'bg-blue-500' : '' }}
                                {{ $appointment->status == 'cancelled' ? 'bg-red-500' : '' }}
                                {{ $appointment->status == 'completed' ? 'bg-green-500' : '' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if ($appointment->doctor->status == 'not_available')
                                <p class="font-bold text-red-700">Dr. {{ $appointment->doctor->user->name }} is currently unavailable.</p>
                            @else
                            <div class="flex space-x-4">
                                <a href="{{ route('view.appointment.details',$appointment->id) }}" class="inline-block px-4 py-2 ml-4 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    View Appointment
                                </a>
                                @if ($appointment->status === 'pending')
                                    <a href="{{ route('edit.myAppoinment.date', $appointment->id) }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-purple-600 rounded shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                                        Edit
                                    </a>
                                    <form action="{{ route('delete.myAppoinment', $appointment->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-red-600 rounded shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                                @if ($appointment->status === 'completed')
                                    <a href="{{ route('view.doctor.review', $appointment->id) }}" class="inline-block px-4 py-2 ml-4 text-sm font-semibold text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                                        View Doctor's Review
                                    </a>
                                @endif
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach


            </tbody>
        </table>
    @endif
</div>
@endsection
