@extends('doctor.doctor_dashboard')

@section('content')
<div class="flex-1 bg-gray-100">
    <div class="px-6 py-12 sm:px-8">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-gray-800">Create Weekly Schedule</h2>
            <p class="mt-4 text-lg text-gray-600">
                {{ __("Please provide your availability for each day of the week. Ensure there are no overlapping schedules.") }}
            </p>

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

            @if(session('success'))
                <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
                    <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('store.schedule') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @php
                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    @endphp

                    @foreach($days as $day)
                        <div class="p-4 rounded-lg bg-gray-50">
                            <label for="{{ $day }}_start_time" class="block text-sm font-medium text-gray-700">{{ $day }}:</label>

                            <div class="mt-2">
                                <label for="{{ $day }}_start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                                <input type="time" id="{{ $day }}_start_time" name="schedule[{{ $day }}][start_time]"
                                    class="block w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required value="{{ old('schedule.'.$day.'.start_time') }}">
                            </div>

                            <div class="mt-4">
                                <label for="{{ $day }}_end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                                <input type="time" id="{{ $day }}_end_time" name="schedule[{{ $day }}][end_time]"
                                    class="block w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required value="{{ old('schedule.'.$day.'.end_time') }}">
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('doctor.dashboard') }}"
                       class="px-4 py-2 mr-2 text-sm font-semibold text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">
                       Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Save Schedule
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
