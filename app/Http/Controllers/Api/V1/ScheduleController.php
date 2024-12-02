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


    public function index()
    {
        $user = Auth::user()->id;
        $doctor = Doctor::where('user_id', $user)->first();
        $schedules = Schedule::where('doctor_id', $doctor->id)->get();

        return response()->json([
            'message' => 'Schedules Retrieved Successfully !!',
            'data' => ScheduleResource::collection($schedules)
        ],200);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
                'errors' => $response['errors'] ?? null,
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => $response['message'],
            'data' => new ScheduleResource($response['data'])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Request $request,string $doctorId)
    // {
    //     $doctorHasAppointments = Appointment::where('doctor_id', $doctorId)->exists();

    //     if ($doctorHasAppointments) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Schedules cannot be deleted as there are appointments associated with this doctor.',
    //         ], 400);
    //     }

    //     Schedule::where('doctor_id', $doctorId)->delete();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'All schedules for the current doctor have been deleted successfully!',
    //     ], 200);
    // }

    public function destroy(Request $request)
    {
        $doctorId = auth()->user()->doctor->id;  // Get the authenticated doctor's id

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

}
