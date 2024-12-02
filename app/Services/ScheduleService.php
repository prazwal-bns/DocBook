<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createSchedule($request, $doctor)
    {
        // Validate the input data (start and end times)
        $validated = $request->validate([
            'schedule.*.start_time' => 'required|date_format:H:i',
            'schedule.*.end_time' => 'required|date_format:H:i|after:schedule.*.start_time',
        ]);

        // Loop through the schedule input for each day
        foreach ($request->schedule as $day => $times) {
            $start_time = Carbon::parse($times['start_time']);
            $end_time = Carbon::parse($times['end_time']);

            // Check if the doctor already has a schedule for the same day
            $existingSchedule = Schedule::where('doctor_id', $doctor->id)
                ->where('day', $day)
                ->first();

            if ($existingSchedule) {
                // Return the error message if an existing schedule is found
                return [
                    'status' => 'error',
                    'message' => "Schedule already exists.",
                ];
            }

            // If no existing schedule is found, create a new schedule
            Schedule::create([
                'doctor_id' => $doctor->id,
                'day' => $day,
                'start_time' => $start_time,
                'end_time' => $end_time,
            ]);
        }

        return [
            'status' => 'success',
            'message' => 'Schedule created successfully!',
        ];
    }

    public function updateSchedule($doctor, $day, $data)
    {
        // Validate the input data
        $validator = Validator::make($data, [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,unavailable'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ];
        }

        // Find the schedule for the doctor and the specified day
        $schedule = Schedule::where('doctor_id', $doctor->id)
            ->where('day', ucfirst(strtolower($day))) // Normalize the day to match case in DB
            ->first();

        // If no schedule is found, return an error
        if (!$schedule) {
            return [
                'status' => 'error',
                'message' => 'Schedule not found for this doctor on the given day.',
            ];
        }

        // Parse the start and end times
        $start_time = Carbon::parse($data['start_time']);
        $end_time = Carbon::parse($data['end_time']);

        // Update the schedule with the new times and status
        $schedule->update([
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $data['status'],
        ]);

        return [
            'status' => 'success',
            'message' => 'Schedule updated successfully!',
            'data' => $schedule
        ];
    }
}
