<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function doctorDashboard(){
        $user = auth()->user();
        $doctor = $user->doctor;
        $appointments = $doctor->appointments;

        return view('doctor.index',compact('user', 'doctor', 'appointments'));
    }
    // end function

    public function doctorProfile(){
        $userId = Auth::user()->id;

        $user = User::find($userId);
        $doctor = Doctor::where('user_id', $userId)->first();

        return view('doctor.edit_profile', compact('user','doctor'));
    }
    // end function

    public function doctorUpdateProfile(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(), // Ensure email is unique except for the current user
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:available,not_available',
            'bio'=> 'nullable|string|min:10|max:500'
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ]);

        $user->doctor->update([
            'status' => $validatedData['status'],
            'bio' => $validatedData['bio'],
        ]);

        session()->flash('success', 'Doctor profile updated successfully!');

        return redirect()->route('doctor.dashboard')->with('success', 'Doctor profile updated successfully!');
    }
    // end function

    public function doctorChangePassword(Request $request){
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
            return redirect()->route('doctor.dashboard')->with('success', 'Password successfully updated!');
        } else {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
    }
    // end function

    public function setUpStatus(Request $request, $userId){
        // Validate the request
        $validatedData = $request->validate([
            'status' => 'required|in:available,not_available',
        ]);

        // Get the doctor
        $doctor = Doctor::where('user_id', $userId)->first();

        // Update the doctor's status
        if ($doctor) {
            $doctor->update([
                'status' => $validatedData['status']
            ]);
        }

        // Redirect back with success message
        return redirect()->route('doctor.dashboard')->with('success', 'Status Updated Successfully!');
    }

}
