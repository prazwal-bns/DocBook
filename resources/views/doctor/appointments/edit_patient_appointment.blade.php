@extends('doctor.doctor_dashboard')
@section('content')
<div class="p-6 my-20 bg-white rounded-lg shadow-lg">
    <h2 class="mb-4 text-2xl font-semibold text-gray-700">Edit Appointment Status</h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-medium text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

        <!-- Error Message -->
    @if ($errors->has('status'))
        <div class="p-4 mb-4 text-sm font-medium text-red-700 bg-red-100 rounded-lg">
            {{ $errors->first('status') }}
        </div>
    @endif

    <!-- Form to Edit Appointment Status -->
    <form action="{{ route('update.patient.appointment',$appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="inline-block px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Update Status
            </button>
        </div>
    </form>
</div>
@endsection
