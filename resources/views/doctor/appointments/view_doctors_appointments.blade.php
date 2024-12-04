@extends('doctor.doctor_dashboard')
@section('content')
<div class="p-6 mx-10 mt-5 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-bold text-gray-700">{{ Auth::user()->name }}'s Appointments</h2>

    @if(session('success'))
        <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
            <i class="mr-2 text-green-600 fas fa-check-circle"></i>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

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

    @if($schedules->isEmpty())
        <div class="p-12 my-4 font-semibold text-red-800 bg-blue-50">
            <p>You have to set up the Schedule to be able to attend patients.</p>
        </div>
    @else
        @if($appointmentData->isEmpty())
            <p class="text-gray-600">You have no appointments at the moment.</p>
        @else
            <table class="w-full border-collapse table-auto">
                <thead>
                    <tr class="bg-blue-100 border-b">
                        <th class="px-4 py-2 text-left">Patient Name</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Day</th>
                        <th class="px-4 py-2 text-left">Start Time</th>
                        <th class="px-4 py-2 text-left">End Time</th>
                        <th class="px-4 py-2 text-left">Appointment Status</th>
                        <th class="px-4 py-2 text-left">Payment Status</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointmentData as $appointment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $appointment->patient->user->name }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                            <td class="px-4 py-2">{{ $appointment->day }}</td>
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
                            
                            <td class="px-4 py-2">
                                @if($appointment->payment->payment_status == 'paid')
                                    <span class="rounded-md px-2 py-1 text-sm font-semibold text-white bg-green-500">Paid</span>
                                    
                                @else
                                    <span class="rounded-md px-2 py-1 text-xs font-semibold text-white bg-red-500">UnPaid</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if ($appointment->doctor->status == 'not_available')
                                    <p class="font-bold text-red-700">You've currently set your availability status to unavailable. Set it to available to view appointments</p>
                                @else
                                    <a href="{{ route('view.a.doctor.appointment',$appointment->id) }}" class="inline-block px-4 py-2 ml-4 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                        View Appointment
                                    </a>
                                    @if ($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                        <a href="{{ route('edit.patient.appointment',$appointment->id) }}" class="inline-block px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                            Edit Status
                                        </a>
                                    @endif

                                    @if ($appointment->status === 'completed')
                                        @if ($appointment->review()->doesntExist())
                                            <a href="{{ route('give.patient.review', $appointment->id) }}" class="inline-block px-4 py-2 ml-4 text-sm font-semibold text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                                                Give Review
                                            </a>
                                        @else
                                            <a href="{{ route('view.patient.review', $appointment->id) }}" class="inline-block px-4 py-2 ml-4 text-sm font-semibold text-white bg-purple-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                                                View Review
                                            </a>
                                        @endif
                                    @endif

                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @endif
</div>
@endsection
