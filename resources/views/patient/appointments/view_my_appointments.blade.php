@extends('patient.patient_dashboard')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md h-96">
    <h2 class="mb-4 text-xl font-bold text-gray-700">My Appointments</h2>

    @if($appointmentData->isEmpty())
        <p class="text-gray-600">You have no appointments at the moment.</p>
    @else
        <table class="w-full border-collapse table-auto">
            <thead>
                <tr class="bg-blue-100 border-b">
                    <th class="px-4 py-2 text-left">Doctor Name</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Start Time</th>
                    <th class="px-4 py-2 text-left">End Time</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointmentData as $appointment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded {{ $appointment->status == 'confirmed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button type="submit" class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                Edit Appoinment
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
