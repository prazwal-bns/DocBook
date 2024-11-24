<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Mail\AppointmentSent;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
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

    public function storeAppointment(AppointmentRequest $request){
        $validated = $request->validated();
        $day = Carbon::parse($validated['appointment_date'])->format('l');
        $validated['day'] = $day;

        $doctor = Doctor::findOrFail($validated['doctor_id']);

        if ($doctor->status === 'not_available') {
            return back()->with('error', 'The selected doctor is currently not available for appointments.');
        }

        // retrive day of appointment for eg: 'Monday'
        $dayOfWeek = Carbon::parse($validated['appointment_date'])->format('l');

        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();

        // Check if the schedule exists and is available
        if (!$schedule) {
            return back()->with('error', 'The doctor does not have a schedule for the selected day.');
        }

        if ($schedule->status !== 'available') {
            return back()->with('error', 'The doctor is currently not available on the selected day.');
        }

        // Parse appointment times
        $appointmentStart = Carbon::parse($validated['start_time']);
        $appointmentEnd = Carbon::parse($validated['end_time']);

        $scheduleStart = Carbon::parse($schedule->start_time);
        $scheduleEnd = Carbon::parse($schedule->end_time);

        // Check if the appointment times are within the doctor's schedule
        if (!$appointmentStart->between($scheduleStart, $scheduleEnd, true) ||
            !$appointmentEnd->between($scheduleStart, $scheduleEnd, true)) {
            return back()->with('error', 'The selected time is not within the doctor\'s available schedule.');
        }


        // All checks passed; create the appointment
        $appointment = Appointment::create([
            'patient_id' => Auth::user()->patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
            'day' => $validated['day']
        ]);

        $doctorEmail = $doctor->user->email;
        Mail::to($doctorEmail)->send(new AppointmentSent($appointment));

        return redirect()->route('view.my.appointment')->with('success', 'Appointment booked successfully!');
    }

    // end function

    public function viewMyAppointment(){
        $userId = Auth::user()->id;
        $patientId = Patient::where('user_id', $userId)->value('id');
        $appointmentData = Appointment::where('patient_id',$patientId)->get();


        return view('patient.appointments.view_my_appointments', compact('appointmentData'));
    }
    // end function

    public function viewDoctorAppointments(){
        $userId = Auth::user()->id;
        $doctorId = Doctor::where('user_id',$userId)->value('id');

        $schedules = Schedule::where('doctor_id',$doctorId)->get();
        $appointmentData = Appointment::where('doctor_id', $doctorId)->get();

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

    // public function updatePatientAppointment(Request $request, $id){
    //     $validated = $request->validate([
    //         'status' => 'required|in:pending,confirmed,completed,cancelled'
    //     ]);

    //     $appointment = Appointment::findOrFail($id);
    //     $appointment->update($validated);

    //     return redirect()->route('view.doctor.appointments')->with('success','Appointment status Updated Successfully !!');
    // }
    public function updatePatientAppointment(Request $request, $id){
        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,completed',
        ]);

        $appointment = Appointment::findOrFail($id);
        $validTransitions = [
            'pending' => ['confirmed'],        // From pending, you can only move to confirmed
            'confirmed' => ['completed'],     // From confirmed, you can only move to completed
            'completed' => [],                // From completed, no transitions allowed
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

    public function updateMyAppointment(AppointmentRequest $request, $appointmentId){
        // Step 1: Validate the request
        $validated = $request->validated();
        $day = Carbon::parse($validated['appointment_date'])->format('l');
        $validated['day'] = $day;

        // Step 2: Retrieve the appointment and check ownership
        $appointment = Appointment::findOrFail($appointmentId);
        $patient = Auth::user()->patient;

        if (!$patient || $appointment->patient_id !== $patient->id) {
            abort(403, 'You are not authorized to edit this appointment.');
        }

        // Ensure the appointment status is 'pending'
        if ($appointment->status !== 'pending') {
            abort(403, 'This appointment is already confirmed and cannot be edited.');
        }

        // Step 3: Retrieve the doctor and check availability
        $doctor = Doctor::findOrFail($validated['doctor_id']);
        if ($doctor->status === 'not_available') {
            return back()->with('error', 'The selected doctor is currently not available for appointments.');
        }

        // Step 4: Check if the doctor has a schedule for the selected day
        $dayOfWeek = Carbon::parse($validated['appointment_date'])->format('l');
        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();

        if (!$schedule) {
            return back()->with('error', 'The doctor does not have a schedule for the selected day.');
        }

        // Step 5: Ensure the doctor is available on the selected day
        if ($schedule->status !== 'available') {
            return back()->with('error', 'The doctor is currently not available on the selected day.');
        }

        // Step 6: Check if the new appointment times fit within the doctor's available schedule
        $appointmentStart = Carbon::parse($validated['start_time']);
        $appointmentEnd = Carbon::parse($validated['end_time']);

        $scheduleStart = Carbon::parse($schedule->start_time);
        $scheduleEnd = Carbon::parse($schedule->end_time);

        if (!$appointmentStart->between($scheduleStart, $scheduleEnd, true) ||
            !$appointmentEnd->between($scheduleStart, $scheduleEnd, true)) {
            return back()->with('error', 'The selected time is not within the doctor\'s available schedule.');
        }

        // Step 7: Update the appointment
        $appointment->update([
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'day' => $validated['day']
        ]);

        // Step 8: Redirect back with success message
        return redirect()->route('view.my.appointment')->with('success', 'Appointment updated successfully!');
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
