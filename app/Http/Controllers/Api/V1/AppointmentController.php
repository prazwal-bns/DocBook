<?php

namespace App\Http\Controllers\Api\V1;

use App\Mail\AppointmentSent;
use App\Models\Payment;
use App\Services\AppointmentService;
use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{

    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function viewAllAppointments(){
        $doctor = Auth::user()->doctor->id;
        $appointments = Appointment::where('doctor_id',$doctor)->get();

        if($appointments->isEmpty()){
            return response()->json([
                'message' => 'You don\'t have any appointments yet.'
            ],200);
        }

        return response()->json([
            'message' => 'Appointments Fetched Succesfully !!',
            'data' => AppointmentResource::collection($appointments)
        ],200);
    }
   
    public function index()
    {
        $patient = Auth::user()->patient->id;
        $appointments = Appointment::where('patient_id',$patient)->get();
        return response()->json([
            'message' => 'Appointments Fetched Succesfully !!',
            'data' => AppointmentResource::collection($appointments)
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(AppointmentRequest $request)
    {
        $validated = $request->validated();

        // Instantiate the service class and call the method
        $appointmentService = new AppointmentService();
        $result = $appointmentService->storeAppointment($validated);

        if ($result['status'] == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
            ], 400);
        }

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => [
                'appointment' => new AppointmentResource($result['appointment']),
                'payment' => $result['payment'],
            ],
        ], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::find($id);
        
        if(!$appointment){
            return response()->json([
                'message' => 'Appointment Not Found'
            ],404);
        }

        return response()->json([
            'message' => 'Appointment with specified id retrieved successfully!!',
            'data' => new AppointmentResource($appointment)
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(AppointmentRequest $request, $appointmentId)
    {
        // Step 1: Retrieve the appointment
        $appointment = Appointment::findOrFail($appointmentId);

        // Check authorization using Gate
        if (Gate::denies('update', $appointment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this appointment.',
            ], 403);
        }

        // Step 2: Use the service to update the appointment
        try {
            $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $request->validated());

            // Step 3: Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Appointment updated successfully!',
                'data' => new AppointmentResource($updatedAppointment),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 403);
        }
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $appointmentId)
    {
        // Find the appointment or return 404 if not found
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment not found.',
            ], 404);
        }

        // Check authorization using Gate
        if (Gate::denies('delete', $appointment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this appointment.',
            ], 403);
        }

        // Ensure the appointment is in a 'pending' status before allowing deletion
        if ($appointment->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'This appointment is already confirmed and cannot be deleted.',
            ], 403);
        }

        // Delete the appointment
        $appointment->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Appointment deleted successfully.',
        ], 200);
    }


    public function updateAppointmentStatus(Request $request, $appointmentId)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,completed',
        ]);

        $appointment = Appointment::find($appointmentId);
    
        // If the appointment does not exist, return a 404 error
        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment not found.',
            ], 404);
        }
    
        // Define valid status transitions
        $validTransitions = [
            'pending' => ['confirmed'],
            'confirmed' => ['completed'],
            'completed' => [],
        ];
    
        $currentStatus = $appointment->status;
        $newStatus = $validatedData['status'];
    
        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            return response()->json([
                'status' => 'error',
                'message' => "Invalid status transition from {$currentStatus} to {$newStatus}.",
            ], 400);
        }
    
        $appointment->status = $newStatus;
        $appointment->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Appointment status updated successfully.',
            'data' => [
                'appointment' => $appointment,
            ],
        ], 200);
    }
    
}
