<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" required :value="old('name')"  autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" required type="email" name="email" :value="old('email')"  autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" class="block w-full mt-1" type="text" name="address" :value="old('address')"  autocomplete="username" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Contact No. -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block w-full mt-1" type="number" name="phone" :value="old('phone')"  autocomplete="username" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>



        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register As')" />
            <div class="flex gap-4">
                <label>
                    <input type="radio" name="role" value="patient" checked onclick="toggleSpecialization(false)">
                    Patient
                </label>
                <label>
                    <input type="radio" name="role" value="doctor" onclick="toggleSpecialization(true)">
                    Doctor
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Specialization Dropdown -->
        {{-- if user selects role as doctor he will have to select his/her specialization via select input --}}

        <div id="specialization-field" class="mt-4" style="display: none;">
            <x-input-label for="specialization_id" :value="__('Specialization')" />
            <select name="specialization_id" id="specialization_id" class="block w-full mt-1">
                <option value="" disabled selected>Select Specialization</option>

                @forelse($specializations as $specialization)
                    <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                @empty
                    <option value="" style="color: red;" disabled>No specializations available</option>
                @endforelse

                @if($specializations->isEmpty())
                    <p class="mt-2 text-red-500">No specializations are currently available. Please contact support for assistance.</p>
                @endif
            </select>
            <x-input-error :messages="$errors->get('specialization_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleSpecialization(show) {
            const field = document.getElementById('specialization-field');
            field.style.display = show ? 'block' : 'none';
        }
    </script>
</x-guest-layout>
