@extends('admin.admin_dashboard')

@section('content')
<div class="flex-1 bg-gray-100">
    <div class="px-6 py-12 sm:px-8">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-gray-800">Edit Specialization</h2>
            <p class="mt-4 text-lg text-gray-600">
                {{ __("Please edit the form below to update the specialization.") }}
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

            <!-- Specialization Form -->
            <form action="{{ route('update.specialization', $specialization->id) }}" method="POST" class="mt-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Specialization Name</label>
                    <input type="text" name="name" id="name"
                           class="block w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter specialization name"
                           value="{{ old('name', $specialization->name) }}" required>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('view.specializations') }}"
                       class="px-4 py-2 mr-2 text-sm font-semibold text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">
                       Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Update Specialization
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
