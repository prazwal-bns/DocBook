@extends('doctor.doctor_dashboard')
@section('content')
    <div class="container flex items-center justify-center min-h-screen px-4 mx-auto mt-10">
        <div class="w-full max-w-6xl p-8 space-y-6 bg-white rounded-lg shadow-lg">
            <h1 class="mb-8 text-3xl font-bold text-center text-gray-700">Welcome to Your Dashboard: {{ auth()->user()->name }}</h1>

            <h2 class="mb-6 text-2xl font-semibold text-center text-gray-600">Edit Your Information</h2>

            <div class="flex space-x-8">
                <!-- Doctor Profile Edit Form in Card -->
                <div class="w-1/2">
                    <div class="p-6 bg-white rounded-lg shadow-lg">
                        <h3 class="mb-4 text-xl font-semibold text-gray-800">Doctor Information</h3>
                        <form action="{{ route('doctor.update.profile') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- User Information -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" required id="name" name="name" value="{{ old('name', $user->name) }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('name'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" required id="email" name="email" value="{{ old('email', $user->email) }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('email'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('phone'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('phone') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('address'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('address') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                                <input required type="text" name="specialization_id" value="{{ $doctor->specialization ? $doctor->specialization->name : 'Not Selected' }}" class="block w-full px-4 py-2 mt-1 text-gray-600 bg-gray-100 border border-gray-300 rounded-md" readonly>
                            </div>

                            @if($doctor->schedules->isNotEmpty())
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="status" name="status"
                                            class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            {{ $doctor->schedules->isEmpty() ? 'disabled' : '' }}>
                                        <option value="available" {{ old('status', $doctor->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="not_available" {{ old('status', $doctor->status) == 'not_available' ? 'selected' : '' }}>Unavailable</option>
                                    </select>
                                    @if($errors->has('status'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('status') }}</p>
                                    @endif
                                </div>
                            @else

                            @endif

                            <div class="mb-4">
                                <label for="bio" class="block text-sm font-medium text-gray-700">Biography</label>
                                <textarea id="bio" name="bio" rows="4" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $doctor->bio) }}</textarea>
                                @if($errors->has('bio'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('bio') }}</p>
                                @endif
                            </div>

                            <div class="flex">
                                <button type="submit" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password Form in Card -->
                <div class="w-1/2">
                    <div class="p-6 bg-white rounded-lg shadow-lg">
                        <h3 class="mb-4 text-xl font-semibold text-gray-800">Change Password</h3>
                        <form action="{{ route('doctor.change.password') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                @if($errors->has('current_password'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('current_password') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" id="new_password" name="new_password"  class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                @if($errors->has('new_password'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('new_password') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"  class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                @if($errors->has('new_password_confirmation'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('new_password_confirmation') }}</p>
                                @endif
                            </div>

                            <div class="flex">
                                <button type="submit" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
