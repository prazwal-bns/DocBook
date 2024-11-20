<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function patientDashboard() {
        $user = auth()->user();
        $patient = $user->patient;
        $appointments = $patient->appointments;

        return view('patient.index', compact('user', 'patient', 'appointments'));
    }

    // end function

    public function patientProfile(){
        // get currently authenticated user
        $userId = Auth::user()->id;

        $user = User::find($userId);

        // check whether the current user_id is in patient table
        $patient = Patient::where('user_id', $userId)->first();
        return view('patient.edit_profile', compact('user','patient'));
    }
    // end function

    public function patientUpdateProfile(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(), // Ensure email is unique except for the current user
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
        ]);

        $user = auth()->user();

        // update only data refering to user table
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'], // Updating email
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ]);

        $user->patient->update([
            'gender' => $validatedData['gender'],
            'dob' => $validatedData['dob'] ? \Carbon\Carbon::parse($validatedData['dob']) : null,
        ]);

        session()->flash('success', 'Patient profile updated successfully!');

        return redirect()->route('patient.dashboard')->with('success', 'Patient profile updated successfully!');
    }
    // end function

    public function patientChangePassword(Request $request){
        $request->validate([
            'current_password' => 'required|string|max:255',
            'new_password' => 'required|confirmed|string|max:255|different:current_password',
            'new_password_confirmation' => 'required',
        ]);

        $user = auth()->user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            session()->flash('success', 'Password successfully updated!');
            return redirect()->route('patient.dashboard')->with('success', 'Password successfully updated!');
        } else {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
    }

}
