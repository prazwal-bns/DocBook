<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        // Example data queries (replace with actual models and logic)
        $totalUsers = User::count(); // Count total users
        $totalDoctors = Doctor::count(); // Count total doctors
        $totalPatients = Patient::count(); // Count total patients
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $completedAppointments = Appointment::where('status', 'completed')->count();

        // Pass data to the view
        return view('admin.index', compact('totalUsers', 'totalDoctors', 'totalPatients', 'pendingAppointments','completedAppointments'));
    }

    // end function

    public function viewPatients(){
        $allPatients = Patient::all();
        return view('admin.view_all_patients', compact('allPatients'));
    }
    // end function

    public function editPatient($id) {
        $patient = Patient::findOrFail($id);
        return view('admin.edit_patient', compact('patient'));
    }
    // end function

    public function adminUpdatePatientProfile(Request $request, $patientId)
    {
        // Find the patient by ID
        $patient = Patient::findOrFail($patientId);

        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $patient->user->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'dob' => 'required|date|before:today',
        ]);

        // Update User Information
        $patient->user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ]);

        // Update Patient Information (if needed)
        $patient->update([
            'gender' => $validatedData['gender'],
            'dob' => $validatedData['dob'],
        ]);

        // Redirect back with success message
        return redirect()->route('view.patients')->with('success', 'Patient profile updated successfully.');
    }
    // end function


    public function adminChangePatientPass(Request $request, $patientId){
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required|min:8',
        ]);

        $patient = Patient::findOrFail($patientId);

        $patient->user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('view.patients')->with('success', 'Password updated successfully.');
    }

    // end function


    public function deletePatient($patientId)
    {
        // Find the patient by their ID
        $patient = Patient::findOrFail($patientId);

        // delete user first
        $patient->user()->delete();

        $patient->delete();
        return redirect()->route('view.patients')->with('success', 'Patient deleted successfully.');
    }
    // end function


    public function viewDoctors(){
        $allDoctors = Doctor::all();
        return view('admin.view_all_doctors', compact('allDoctors'));
    }
    // end function

    public function editDoctor($id){
        $doctor = Doctor::findOrFail($id);
        $specializations = Specialization::all();
        return view('admin.edit_doctor', compact('doctor','specializations'));
    }
    // end function

    public function adminUpdateDoctorProfile(Request $request, $id){
        $doctor = Doctor::findOrFail($id);

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $doctor->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:available,not_available',
            'bio' => 'nullable|string',
            'specialization_id' => 'required|exists:specializations,id',
        ]);

        $user = $doctor->user;
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update doctor-specific information
        $doctor->update([
            'status' => $request->status,
            'bio' => $request->bio,
            'specialization_id' => $request->specialization_id,
        ]);

        return redirect()->route('view.doctors')
        ->with('success', 'Doctor profile updated successfully.');
    }
    // end function

    public function adminChangeDoctorPass(Request $request , $doctorId){
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required|min:8',
        ]);

        $doctor = Doctor::findOrFail($doctorId);

        $doctor->user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('view.doctors')->with('success', 'Password updated successfully.');
    }
    // end function

    public function deleteDoctor($doctorId){
        $doctor = Doctor::findOrFail($doctorId);
        $doctor->user->delete();

        $doctor->delete();
        return redirect()->route('view.doctors')->with('success', 'Doctor deleted successfully.');
    }
}
