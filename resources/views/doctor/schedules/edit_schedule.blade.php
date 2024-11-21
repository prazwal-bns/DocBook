@extends('doctor.doctor_dashboard')
@section('content')
    <div class="container p-4 mx-auto">
        <div class="max-w-lg p-6 mx-auto mt-12 bg-white rounded-lg shadow-md">
            <h2 class="mb-8 text-2xl font-semibold text-gray-800">Edit Schedule for {{ $schedule->day }}</h2>

            <form action="{{ route('update.schedule', $schedule->id) }}" method="POST" class="mt-8 mb-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Start Time -->
                    <div class="mb-4">
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                        <input type="time" name="start_time" id="start_time" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}"
                            class="block w-full mt-2 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror" required>

                        <!-- Display Start Time Error -->
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div class="mb-4">
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                        <input type="time" name="end_time" id="end_time" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}"
                            class="block w-full mt-2 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror" required>

                        <!-- Display End Time Error -->
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Dropdown -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Schedule Status:</label>
                    <select name="status" id="status" class="block w-full mt-2 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="available" {{ $schedule->status === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ $schedule->status === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>


                <div class="flex justify-end mt-8">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Update Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
