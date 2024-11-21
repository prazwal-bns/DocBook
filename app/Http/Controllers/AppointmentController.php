<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function storeAppointment(Request $request){
        // Validate the incoming request data
        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        // Retrieve the doctor by ID
        $doctor = Doctor::findOrFail($validated['doctor_id']);

        // Check if the doctor is available
        if ($doctor->status === 'not_available') {
            return back()->with('error', 'The selected doctor is currently not available for appointments.');
        }

        // Get the day of the week from the selected appointment date
        $dayOfWeek = Carbon::parse($validated['appointment_date'])->format('l'); // e.g., 'Monday'

        // Find the doctor's schedule for the selected day
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

        // Check for overlapping appointments
        $overlappingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $validated['appointment_date'])
            ->where(function ($query) use ($appointmentStart, $appointmentEnd) {
                $query->where(function ($q) use ($appointmentStart, $appointmentEnd) {
                    $q->where('start_time', '<', $appointmentEnd) // Starts before new appointment ends
                    ->where('end_time', '>', $appointmentStart); // Ends after new appointment starts
                });
            })
            ->exists();

        if ($overlappingAppointments) {
            return back()->with('error', 'The selected time overlaps with another appointment. Please choose a different time.');
        }

        // All checks passed; create the appointment
        Appointment::create([
            'patient_id' => Auth::user()->patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Appointment booked successfully!');
    }


    // end function

    public function viewMyAppointment(){
        $userId = Auth::user()->id;
        $patientId = Patient::where('user_id', $userId)->value('id');
        $appointmentData = Appointment::where('patient_id',$patientId)->get();

        return view('patient.appointments.view_my_appointments', compact('appointmentData'));
    }

}
