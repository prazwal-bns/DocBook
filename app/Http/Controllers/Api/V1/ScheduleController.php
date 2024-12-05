<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ScheduleResource;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
        *
        * Get Doctor's Schedule
        *
            * - Retrieves the logged-in doctor's schedule.
            * - If no schedule is found for the doctor, returns a message indicating the absence of a schedule.
            * - If the schedule exists, returns the doctor's schedule data in the response.
        *
    */

    public function index()
    {
        $user = Auth::user()->id;
        $doctor = Doctor::where('user_id', $user)->first();
        $schedules = Schedule::where('doctor_id', $doctor->id)->get();

        if($schedules->isEmpty()){
            return response()->json([
                'message' => 'You currently have no Schedule.'
            ],200);
        }

        return response()->json([
            'message' => 'Schedules Retrieved Successfully !!',
            'data' => ScheduleResource::collection($schedules)
        ],200);
    }

    /**
        *
        * Store Doctor's Schedule for a Week
        *
            * - Retrieves the logged-in doctor using `auth()->user()->doctor`.
            * - Calls the `createSchedule` method of the `ScheduleService` to create a schedule for the doctor.
            * - If the schedule creation fails (error status), returns an error response with the corresponding message.
            * - If successful, creates the schedule for the week and returns a success response with the success message.
            * - Handles the creation of schedules and manages the response based on the result.
        *
    */

    public function store(Request $request)
    {
        $doctor = auth()->user()->doctor;

        // Call the createSchedule method from the ScheduleService
        $result = $this->scheduleService->createSchedule($request, $doctor);

        // Check if the result is an error, if so return the error response
        if ($result['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
        ], 201);
    }


    /**
        *
        * Show Doctor's Schedule for a Specific Day
        *
            * - Converts the given `id` to the proper case (first letter capitalized).
            * - Retrieves the logged-in doctor's schedule for the specified day.
            * - If no schedule is found for the doctor on that day, returns a 404 error with a relevant message.
            * - If a schedule is found, returns the schedule data in the response with a success status.
            * - The schedule is wrapped in a `ScheduleResource` to format the response appropriately.
        *
    */

    public function show(string $id)
    {
        $day = ucfirst(strtolower($id));  
        $doctor = auth()->user()->doctor;
    
        // Retrieve the schedule for the specific doctor and day
        $schedule = Schedule::where('doctor_id', $doctor->id)
            ->where('day', $day)
            ->first();
    
        // If no schedule is found, return a 404 response
        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule not found for this day.',
            ], 404);
        }
    
        // Return the schedule data as a response
        return response()->json([
            'status' => 'success',
            'data' => new ScheduleResource($schedule),
        ], 200);
    }

    /**
        *
        * Update Doctor's Schedule for a Specific Day
        *
            * - Retrieves the logged-in doctor's information.
            * - Calls the `updateSchedule` method from the `ScheduleService` to handle the update logic.
            * - If the update fails or returns an error, returns a 422 error with an appropriate error message.
            * - If the update is successful, returns a success response with the updated schedule data formatted by `ScheduleResource`.
            * - The schedule data is returned in the response, including the success message.
        *
    */


    public function update(Request $request, string $day)
    {
        $doctor = auth()->user()->doctor;

        // Use the service to handle the update logic
        $response = $this->scheduleService->updateSchedule($doctor, $day, $request->all());

        if ($response['status'] == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $response['message'],
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => $response['message'],
            'data' => new ScheduleResource($response['data'])
        ], 200);
    }

    /**
        *
        * Delete Doctor's Schedules
        *
            * - Retrieves the logged-in doctor's ID.
            * - Checks if the doctor has any associated appointments.
            * - If the doctor has appointments, returns a 400 error with a message indicating schedules cannot be deleted.
            * - If no appointments are associated, deletes all schedules for the current doctor.
            * - Returns a success response with a confirmation message upon successful deletion of the schedules.
        *
    */


    public function destroy(Request $request)
    {
        $doctorId = auth()->user()->doctor->id; 

        $doctorHasAppointments = Appointment::where('doctor_id', $doctorId)->exists();

        if ($doctorHasAppointments) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedules cannot be deleted as there are appointments associated with this doctor.',
            ], 400);
        }

        Schedule::where('doctor_id', $doctorId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'All schedules for the current doctor have been deleted successfully!',
        ], 200);
    }
    // end function

    /**
        *
        * View Doctor's Weekly Schedules
        *
            * - Retrieves the doctor by the provided doctorId.
            * - If the doctor doesn't exist, returns a 404 error with a message indicating the doctor does not exist.
            * - If the doctor is marked as 'not_available', returns a 400 error with a message indicating the doctor is not available.
            * - Retrieves the doctor's schedules.
            * - If no schedules are found, returns a 404 error indicating there are no available schedules.
            * - If schedules are found, returns a success response with the doctor's weekly schedule.
        *
    */

    public function viewWeeklySchedules($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected doctor does not exist.',
            ], 404);
        }
    
        if ($doctor->status === 'not_available') {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected doctor is currently not available.',
            ], 400);
        }
    
        $schedules = $doctor->schedules()->get();
    
        if ($schedules->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected doctor has no schedules available.',
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Doctor\'s weekly schedule retrieved successfully.',
            'data' => ScheduleResource::collection($schedules),
        ], 200);
    }
    
    // end function
}
