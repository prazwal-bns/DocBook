@extends('patient.patient_dashboard')
@section('content')
<div class="p-6 mt-8 mb-8 rounded-lg shadow-md bg-blue-50">
    <h1 class="mb-4 text-xl font-bold text-gray-700">Doctors Specializing in {{ $specialization->name }}</h1>

    @if($doctors->isEmpty())
        <p class="text-gray-600 min-h-60">No doctors are available for this specialization at the moment.</p>
    @else
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach($doctors as $doctor)
                <div class="p-4 transition duration-300 ease-in-out transform bg-white border border-gray-300 rounded-lg shadow hover:shadow-lg hover:bg-gray-50 hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <!-- Doctor's Profile Image Placeholder -->
                        <div class="w-24 h-24 mb-3 overflow-hidden bg-gray-200 rounded-full">
                            <img src="https://static.vecteezy.com/system/resources/previews/015/412/022/non_2x/doctor-round-avatar-medicine-flat-avatar-with-male-doctor-medical-clinic-team-round-icon-medical-collection-illustration-vector.jpg"
                                 alt="{{ $doctor->user->name }}"
                                 class="object-cover w-full h-full">
                        </div>


                        <!-- Doctor's Details -->
                        <h2 class="text-2xl font-semibold text-gray-700">{{ $doctor->user->name }}</h2>
                        <p class="mt-2 text-sm text-gray-600">{{ $doctor->user->email }}</p>
                        <p class="mt-2 text-sm text-gray-600">Phone No: {{ $doctor->user->phone }}</p>

                        <!-- Book Appointment Button -->
                        <a href="{{ route('book.appointment', $doctor->id) }}"
                           class="inline-block px-4 py-2 mt-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Book Appointment
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
