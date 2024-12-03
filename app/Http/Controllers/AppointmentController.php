<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Mail\AppointmentSent;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Specialization;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    
    public function makeAppointment(){
        $specializations = Specialization::all();
        return view('patient.appointments.make_appointment', compact('specializations'));
    }
    // end function

    public function viewDoctorsBySpecialization($specializationId)
    {
        $doctors = Doctor::where('specialization_id', $specializationId)
                    ->whereHas('schedules') // Ensure the doctor has schedules
                    ->where('status', 'available')
                    ->get();

        $specialization = Specialization::findOrFail($specializationId);
        return view('patient.appointments.view_doctors', compact('doctors', 'specialization'));
    }
    // end function

    public function bookAppointment($doctorId){
        $doctor = Doctor::findOrFail($doctorId);

        return view('patient.appointments.book_appointment',compact('doctor'));
    }
    // end function


    public function storeAppointment(AppointmentRequest $request)
    {
        $validated = $request->validated();

        // Instantiate the service class and call the method
        $appointmentService = new AppointmentService();
        $result = $appointmentService->storeAppointment($validated);

        if ($result['status'] == 'error') {
            return back()->with('error', $result['message']);
        }

        // Redirect with success message
        return redirect()->route('view.my.appointment')->with('success', $result['message']);
    }

    // end function

    public function viewMyAppointment(){
        $userId = Auth::user()->id;
        $patientId = Patient::where('user_id', $userId)->value('id');
        $appointmentData = Appointment::where('patient_id',$patientId)->latest('status')->paginate(5);

        return view('patient.appointments.view_my_appointments', compact('appointmentData'));
    }
    // end function

    public function viewDoctorAppointments(){
        $userId = Auth::user()->id;
        $doctorId = Doctor::where('user_id',$userId)->value('id');

        $schedules = Schedule::where('doctor_id',$doctorId)->get();
        $appointmentData = Appointment::where('doctor_id', $doctorId)->latest('appointment_date')->get();

        return view('doctor.appointments.view_doctors_appointments', compact('appointmentData','schedules'));
    }
    // end function

    public function editPatientAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Fetch the doctor's record for the authenticated user
        $doctor = Auth::user()->doctor;

        // Check if the authenticated user is the doctor associated with the appointment
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'You are not authorized to edit this appointment.');
        }

        if ($appointment->status === 'completed') {
            abort(403, 'This appointment is already completed and cannot be edited.');
        }

        return view('doctor.appointments.edit_patient_appointment', compact('appointment'));
    }
    // end function

    public function updatePatientAppointment(Request $request, $id){
        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,completed',
        ]);

        $appointment = Appointment::findOrFail($id);
        $validTransitions = [
            'pending' => ['confirmed'],      
            'confirmed' => ['completed'],    
            'completed' => [],               
        ];

        $currentStatus = $appointment->status;
        $newStatus = $validatedData['status'];

        // Check if the transition is valid
        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            return redirect()->back()->withErrors([
                'status' => "Invalid status transition from {$currentStatus} to {$newStatus}.",
            ]);
        }

        // Update the status
        $appointment->status = $newStatus;
        $appointment->save();

        return redirect()->route('view.doctor.appointments')->with('success', 'Appointment status updated successfully.');
    }

    // end function

    public function viewADoctorAppointment($id){
        $appointment = Appointment::findOrFail($id);

        $doctor = Auth::user()->doctor;

        // Check if the authenticated user is the doctor associated with the appointment
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'You are not authorized to view this appointment details.');
        }

        return view('doctor.appointments.view_a_patient_appointment', compact('appointment'));
    }
    // end function

    public function viewMyAppointmentDetails($id){
        $appointment = Appointment::findOrFail($id);
        $patient = Auth::user()->patient;

        if(!$patient || $appointment->patient_id !== $patient->id){
            abort(403, 'You are not authorized to view this appointment details.');
        }

        return view('patient.appointments.view_my_appointment_details', compact('appointment'));
    }
    // end function

    public function editMyAppointmentDate($appointmentId){
        $appointment = Appointment::findOrFail($appointmentId);
        $patient = Auth::user()->patient;

        Gate::authorize('update',$appointment);

        if(!$patient || $appointment->patient_id !== $patient->id){
            abort(403, 'You are not authorized to edit this appointment.');
        }

        if ($appointment->status !== 'pending') {
            abort(403, 'This appointment is already confirmed and cannot be edited.');
        }

        return view('patient.appointments.edit_appointment_date', compact('appointment'));
    }
    // end function

    public function updateMyAppointment(AppointmentRequest $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // Gate::authorize('update',$appointment);

        // Step 2: Use the service to update the appointment
        try {
            $this->appointmentService->updateAppointment($appointment, $request->validated());
            return redirect()->route('view.my.appointment')->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    // end function

    public function deleteMyAppointment($appointmentId){
        $appointment = Appointment::findOrFail($appointmentId);
        Gate::authorize('delete',$appointment);
        $patient = Auth::user()->patient;


        if (!$patient || $appointment->patient_id !== $patient->id) {
            abort(403, 'You are not authorized to delete this appointment.');
        }

        if($appointment->status !== 'pending'){
            abort(403, 'This appointment is already confirmed and cannot be edited.');
        }

        $appointment->delete();
        return redirect()->route('view.my.appointment')->with('success', 'Appointment Deleted successfully!');
    }
}
