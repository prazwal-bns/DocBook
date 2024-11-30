<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $specializations = Specialization::all();
        return view('auth.register', compact('specializations'));
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string','min:10','max:10'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:patient,doctor'],
            'specialization_id' => ['required_if:role,doctor', 'exists:specializations,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if($request->role === 'doctor'){
            Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $request->specialization_id,
                'status' => 'available',
                'bio' => $request->input('bio') ?? null,
            ]);

            $redirectRoute = 'doctor.dashboard';
        }

        elseif($request->role === 'patient'){
            Patient::create([
                'user_id' => $user->id,
                'gender' => $request->input('gender',''),
                'dob' => $request->input('dob') ?? null,
            ]);

            $redirectRoute = 'patient.dashboard';
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route($redirectRoute));
    }
}
