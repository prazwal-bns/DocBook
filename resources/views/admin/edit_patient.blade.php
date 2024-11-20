@extends('admin.admin_dashboard')
@section('content')
    <div class="container flex items-center justify-center min-h-screen px-4 mx-auto mt-10">
        <div class="w-full max-w-6xl p-8 space-y-6 bg-white rounded-lg shadow-lg">
            <h1 class="mb-8 text-3xl font-bold text-center text-gray-700">Edit Patient's Profile</h1>

            <h2 class="mb-6 text-2xl font-semibold text-center text-gray-600">Edit Patient Information</h2>

            <div class="flex space-x-8">
                <!-- Patient Profile Edit Form in Card -->
                <div class="w-1/2">
                    <div class="p-6 bg-white rounded-lg shadow-lg">
                        <h3 class="mb-4 text-xl font-semibold text-gray-800">User Information</h3>
                        <form action="{{ route('admin.update.patientProfile', $patient->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- User Information -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $patient->user->name) }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('name'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $patient->user->email) }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('email'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $patient->user->phone) }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('phone'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('phone') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $patient->user->address) }}" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @if($errors->has('address'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('address') }}</p>
                                @endif
                            </div>

                            <!-- Patient Information -->
                            <h3 class="mt-6 text-xl font-semibold text-gray-800">Patient Information</h3>
                            <div class="mb-4">
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select id="gender" name="gender" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" {{ old('gender', $patient->gender) === null ? 'selected' : '' }}>Not Selected</option>
                                    <option value="male" {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @if($errors->has('gender'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('gender') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input
                                    type="date"
                                    id="dob"
                                    name="dob"
                                    value="{{ old('dob', $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : '') }}"
                                    class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="mm/dd/yyyy"
                                >
                                @if($errors->has('dob'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('dob') }}</p>
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

                <!-- Change Patient Password Form in Card -->
                <div class="w-1/2">
                    <div class="p-6 bg-white rounded-lg shadow-lg">
                        <h3 class="mb-4 text-xl font-semibold text-gray-800">Change Patient Password</h3>
                        <form action="{{ route('admin.patient.changePassword', $patient->id) }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="mb-4">
                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                @if($errors->has('new_password'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('new_password') }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
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
