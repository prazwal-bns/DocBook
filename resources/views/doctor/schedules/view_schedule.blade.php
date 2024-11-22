@extends('doctor.doctor_dashboard')

@section('content')
<div class="flex-1 p-6 bg-gray-100 sm:p-8">
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-3xl font-semibold text-gray-800">{{ $doctor->user->name }}'s Schedule</h2>
        <p class="mt-4 text-lg text-gray-600">
            {{ __("Here are your schedules for this week.") }}
        </p>

        @if(session('success'))
            <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
                <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center px-6 py-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-md">
                <i class="mr-2 text-red-600 fas fa-exclamation-circle"></i>
            {{session('error')}}
            </div>
        @endif

        @if(count($schedules) > 0)
            <table class="min-w-full mt-6 table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-2xl font-medium text-left text-gray-700">Day</th>
                        <th class="px-4 py-2 text-2xl font-medium text-left text-gray-700">Start Time</th>
                        <th class="px-4 py-2 text-2xl font-medium text-left text-gray-700">End Time</th>
                        <th class="px-4 py-2 text-2xl font-medium text-left text-gray-700">Action</th>
                        <th class="px-4 py-2 text-2xl font-medium text-left text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($schedules as $schedule)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $schedule->day }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                            <td class="flex px-6 py-4 space-x-4">
                                <a href="{{ route('edit.schedule', $schedule->id) }}"
                                    class="inline-block px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Edit
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <span id="status-text-{{ $schedule->id }}" class="px-2 py-1 text-xs font-semibold text-white rounded {{ $schedule->status === 'available' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                <form action="{{ route('delete.schedule', $doctor->id) }}" method="POST">
                   @csrf
                   @method('DELETE')
                   <button class="inline-block px-6 py-4 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                            onclick="return confirm('Are you sure you want to delete all schedules for this doctor?')">
                        Delete All Schedules
                    </button>
                </form>
            </div>
        @else
            <!-- No schedules found message -->
            <div class="mt-6 text-center text-gray-600">
                <p class="text-3xl font-semibold text-red-700">No Schedule Found</p>
                <p class="mt-4 text-sm">It looks like you have no schedule.</p>

                <!-- Button to Add Schedule -->
                <div class="mt-4">
                    <a href="{{ route('add.schedule') }}"
                       class="inline-block px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Add Schedule
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
