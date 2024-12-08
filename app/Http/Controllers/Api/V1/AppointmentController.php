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

    /**
        *
        * View All Appointments for the Authenticated Doctor
        *
            * - Fetches all appointments assigned to the logged-in doctor.
            * - Returns a list of appointments with details like patient, scheduled date, and status.
            * - If no appointments are found, a message indicating the absence of appointments is returned.
            *
        *
     */
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
   

    /**
        *
        * View All Appointments for the Authenticated Patient
        *
            * - Fetches all appointments associated with the logged-in patient.
            * - Returns a list of appointments with details such as the doctor, scheduled date, and status.
            * - If no appointments are found, an empty list is returned.
        *
    */

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
        *
        * Store a Newly Created Appointment
        *
            * - Validates the incoming appointment request data.
            * - Calls the service class to store the appointment and payment details.
            * - If the appointment creation fails, returns an error message.
            * - If the appointment is successfully created, returns the appointment and payment details with a success message.
            * - A 201 HTTP status code is returned on success, while a 400 status code is returned on failure.
        *
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
        *
        * View Appointment by ID
        *
            * - Fetches a specific appointment based on the provided appointment ID.
            * - If the appointment is found, returns the appointment details.
            * - If no appointment is found, returns a "not found" message with a 404 status.
            * - Returns the appointment details with a 200 status on success.
        *
    */

    public function show(string $id)
    {
        $appointment = Appointment::find($id);
        
        // Gate::authorize('view',$appointment);
        if(Gate::denies('view',$appointment)){
            return response()->json([
               'message' => 'You are not authorized to view this appointment.'
            ], 403);
        }

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
        *
        * Update an Existing Appointment
        *
            * - Retrieves the appointment by the provided appointment ID.
            * - Checks if the user is authorized to update the appointment using Gate.
            * - If not authorized, returns a 403 error with a message.
            * - Uses the service class to update the appointment details.
            * - If the update is successful, returns the updated appointment details with a success message.
            * - If an error occurs, returns an error message with a 403 status code.
        *
    */

    // public function update(AppointmentRequest $request, $appointmentId)
    // {
    
    //     $appointment = Appointment::findOrFail($appointmentId);

    //     if (Gate::denies('update', $appointment)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'You are not authorized to update this appointment.',
    //         ], 403);
    //     }

    //     // Step 2: Use the service to update the appointment
    //     try {
    //         $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $request->validated());

    //         // Step 3: Return success response
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Appointment updated successfully!',
    //             'data' => new AppointmentResource($updatedAppointment),
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 403);
    //     }
    // }

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
            // Get validated data from the request
            $validatedData = $request->validated();

            // Ensure 'doctor_id' is not part of the update payload
            // as the patient cannot change the doctor
            if (isset($validatedData['doctor_id'])) {
                unset($validatedData['doctor_id']);
            }

            // Make sure the patient cannot change the doctor in the update
            $validatedData['doctor_id'] = $appointment->doctor_id; // Use the doctor_id from the existing appointment

            // Update the appointment using the service
            $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $validatedData);

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
            ], 500); // Return appropriate error status
        }
    }

    

    
    /**
        *
        * Delete an Appointment
        *
            * - Finds the appointment by the provided appointment ID or returns a 404 if not found.
            * - Checks if the user is authorized to delete the appointment using Gate.
            * - If not authorized, returns a 403 error with a message.
            * - Ensures the appointment is in a 'pending' status before allowing deletion.
            * - If the appointment is confirmed, returns a 403 error indicating it cannot be deleted.
            * - Deletes the appointment if all conditions are met and returns a success message.
        *
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


    /**
        *
        * Update the Status of an Appointment
        *
            * - Validates the new status for the appointment, ensuring it is one of: pending, confirmed, or completed.
            * - Finds the appointment by ID and returns a 404 error if not found.
            * - Checks if the status transition is valid based on the current status of the appointment.
            * - If the transition is invalid, returns a 400 error indicating the invalid status change.
            * - If the transition is valid, updates the appointment status and saves it.
            * - Returns a success response with the updated appointment data.
        *
    */

    // public function updateAppointmentStatus(Request $request, $appointmentId)
    // {
    //     $validatedData = $request->validate([
    //         'status' => 'required|in:confirmed,completed',
    //     ]);

    //     $appointment = Appointment::find($appointmentId);
    
    //     // If the appointment does not exist, return a 404 error
    //     if (!$appointment) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Appointment not found.',
    //         ], 404);
    //     }
    
    //     // Define valid status transitions
    //     $validTransitions = [
    //         'pending' => ['confirmed'],
    //         'confirmed' => ['completed'],
    //         'completed' => [],
    //     ];
    
    //     $currentStatus = $appointment->status;
    //     $newStatus = $validatedData['status'];
    
    //     if (!in_array($newStatus, $validTransitions[$currentStatus])) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => "Invalid status transition from {$currentStatus} to {$newStatus}.",
    //         ], 400);
    //     }
    
    //     $appointment->status = $newStatus;
    //     $appointment->save();
    
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Appointment status updated successfully.',
    //         'data' => [
    //             'appointment' => $appointment,
    //         ],
    //     ], 200);
    // }

    public function updateAppointmentStatus(Request $request, $appointmentId)
    {
        // Find the appointment or return a 404 error
        $appointment = Appointment::findOrFail($appointmentId);

        // Check if the authenticated user is the doctor assigned to the appointment
        $doctor = Auth::user()->doctor;
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to edit this appointment.',
            ], 403);
        }

        // Check if the appointment is already completed
        if ($appointment->status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This appointment is already completed and cannot be edited.',
            ], 403);
        }

        // Check if the appointment is still pending
        if ($appointment->status === 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment for this appointment is still pending and cannot be edited at the moment.',
            ], 403);
        }

        // Validate the incoming status change
        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,completed'],
        ]);

        // Handle status transitions
        if ($appointment->status === 'confirmed' && $request->status === 'completed') {
            $appointment->update(['status' => 'completed']);

            return response()->json([
                'status' => 'success',
                'message' => 'Appointment status updated successfully.',
                'data' => [
                    'appointment' => $appointment,
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid status change attempt.',
        ], 400);
    }

    
}
