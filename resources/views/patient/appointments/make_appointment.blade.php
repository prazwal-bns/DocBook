@extends('patient.patient_dashboard')
@section('content')
<div class="p-6 rounded-lg shadow-md">
    <h1 class="mb-4 text-xl font-bold text-gray-700">Select a Specialization</h1>

    <div class="grid gap-6 mt-6 mb-12 md:grid-cols-2 sm:grid-cols-2">

        @foreach($specializations as $specialization)
            <a href="{{ route('view.doctorsBySpecialization', $specialization->id) }}"
               class="block p-4 transition duration-300 ease-in-out transform border border-blue-300 rounded-lg shadow hover:bg-blue-200 hover:shadow-lg hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-blue-600">{{ $specialization->name }}</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        {{-- <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /> --}}
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection


