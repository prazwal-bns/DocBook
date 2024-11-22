<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function viewSchedule(){
        $user = Auth::user()->id;
        $doctor = Doctor::where('user_id', $user)->first();

        $schedules = Schedule::where('doctor_id', $doctor->id)->get();
        return view('doctor.schedules.view_schedule', compact('schedules','doctor'));
    }
    // end function

    public function addSchedule(){
        return view('doctor.schedules.add_schedule', );
    }
    // end function

    public function storeSchedule(Request $request)
    {
        // Validate the input data (start and end times)
        $validated = $request->validate([
            'schedule.*.start_time' => 'required|date_format:H:i',
            'schedule.*.end_time' => 'required|date_format:H:i|after:schedule.*.start_time',
        ]);

        // Get the logged-in doctor
        $doctor = auth()->user()->doctor;


        /* The $request->schedule is stored in this format
        array:7 [▼ // app\Http\Controllers\ScheduleController.php:30
        "Sunday" => array:2 [▼
            "start_time" => "14:36"
            "end_time" => "12:13"
        ]
        */

        // Loop through the schedule input for each day
        foreach ($request->schedule as $day => $times) {
            $start_time = Carbon::parse($times['start_time']);
            $end_time = Carbon::parse($times['end_time']);

            // Check if the doctor already has a schedule for the same day
            $existingSchedule = Schedule::where('doctor_id', $doctor->id)
                ->where('day', $day)
                ->first();

            if ($existingSchedule) {
                // If an existing schedule is found, redirect to edit page
                return redirect()->route('view.schedule')
                    ->with('error', 'You already have a schedule. You cannot add another schedule.',);
            }

            // If no existing schedule is found, create a new schedule
            Schedule::create([
                'doctor_id' => $doctor->id,
                'day' => $day,
                'start_time' => $start_time,
                'end_time' => $end_time,
            ]);
        }

        return redirect()->route('view.schedule')->with('success', 'Schedule created successfully!');
    }
    // end function

    // public function editSchedule($id){
    //     $schedule = Schedule::findOrFail($id);
    //     return view('doctor.schedules.edit_schedule', compact('schedule'));
    // }

    // issue
    public function editSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Check if there are appointments for the doctor specifically on the schedule's day
        $hasAppointmentsOnDay = Appointment::where('doctor_id', $schedule->doctor_id)
            ->where('day', $schedule->day) // Check if the day matches
            ->exists();

        if ($hasAppointmentsOnDay) {
            return redirect()->route('view.schedule')->with('error', 'This schedule cannot be edited as it is associated with appointments on this day.');
        }

        return view('doctor.schedules.edit_schedule', compact('schedule'));
    }
    // end funciton

    public function updateSchedule(Request $request, $id){
        $validatedData = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,unavailable'
        ]);

        $schedule = Schedule::findOrFail($id);

        $start_time = Carbon::parse($request->start_time);
        $end_time = Carbon::parse($request->end_time);

        $schedule->update([
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $validatedData['status']
        ]);

        return redirect()->route('view.schedule')->with('success', 'Schedule updated successfully!');
    }
    // end function

    public function deleteSchedule($doctorId){
        // Check if the doctor has any active appointments
        $doctorHasAppointments = Appointment::where('doctor_id', $doctorId)->exists();

        if ($doctorHasAppointments) {
            return redirect()->route('view.schedule')->with('error', 'Schedules cannot be deleted as there are appointments associated with this doctor.');
        }

        // If no appointments exist, delete all schedules for the doctor
        Schedule::where('doctor_id', $doctorId)->delete();

        return redirect()->route('view.schedule')->with('success', 'All schedules for the current doctor have been deleted successfully!');
    }

    // end function


}
