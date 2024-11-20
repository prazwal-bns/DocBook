@extends('admin.admin_dashboard')
@section('content')
<div class="flex-1 bg-gray-100">
    <div class="px-6 py-12 sm:px-8">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            @if(session('success'))
                <div class="flex items-center px-6 py-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-md">
                    <!-- Success icon -->
                    <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <!-- Display error message with icon -->
            @if($errors->any())
                <div class="flex items-center px-6 py-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-md">
                    <!-- Error icon -->
                    <i class="mr-2 text-red-600 fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="text-3xl font-semibold text-gray-800">All Patients</h2>
            <p class="mt-4 text-lg text-gray-600">
                {{ __("Below is a list of all patients. You can view, edit, or delete patient records.") }}
            </p>

            <!-- Patients Table -->
            <div class="mt-8 overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
                    <thead>
                        <tr class="text-sm font-medium text-left text-gray-700 bg-gray-100">
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Phone</th>
                            <th class="px-6 py-4">Address</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allPatients as $patient)
                        <tr class="text-sm text-gray-700 border-t border-gray-200">
                            <td class="px-6 py-4">{{ $patient->user->name }}</td>
                            <td class="px-6 py-4">{{ $patient->user->email }}</td>
                            <td class="px-6 py-4">{{ $patient->user->phone }}</td>
                            <td class="px-6 py-4">{{ $patient->user->address }}</td>
                            <td class="flex px-6 py-4 space-x-4">

                                <!-- Edit Button -->
                                <a href="{{ route('edit.patient',$patient->id) }}" class="inline-block px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('delete.patient',$patient->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50" onclick="return confirm('Are you sure you want to delete this patient?')">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
