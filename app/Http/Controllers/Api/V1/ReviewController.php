<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    /**
        *
        * Save Doctor's Review for a Completed Appointment
        *
            * - Validates the review message to ensure it's a non-empty string.
            * - Fetches the appointment by its ID and checks if the logged-in user is authorized to add a review for the appointment.
            * - Ensures the appointment is in the 'completed' status before allowing a review.
            * - Prevents adding multiple reviews for the same appointment.
            * - Saves the review and returns a success message with the review data.
            * - If any condition is violated (invalid status or existing review), returns an appropriate error message.
        *
    */

    public function saveReview(Request $request,$appointmentId){
        $validatedData = $request->validate([
            'review_msg' => 'required|string'
        ]);

        $appointment = Appointment::findOrFail($appointmentId);
        
        Gate::authorize('addReview',$appointment);

        if ($appointment->status!== 'completed') {
            return response()->json(['error'=>'Only completed appointments can have reviews.'],403);
        }

        if($appointment->review()->exists()) {
            return response()->json(['error'=>'The appointment already contains a review, you can\'t give another review.'],403);
        }

        $review = new Review();
        $review->appointment_id = $appointmentId;
        $review->review_msg = $validatedData['review_msg'];
        $review->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Review added successfully!!',
            'data' => $validatedData
        ],200);
    }
    // end function

    /**
        *
        * View Review for a Specific Appointment [Doctor]
        *
            * - Fetches the appointment along with its associated review.
            * - Authorizes the user to view the review for the specified appointment.
            * - If no review is found for the appointment, returns an error message.
            * - If a review exists, retrieves and returns the review details including review message and appointment ID.
        *
    */

    public function viewAppointmentReview($appointmentId){
        $appointment = Appointment::with('review')->findOrFail($appointmentId);

        Gate::authorize('viewReview',$appointment);

        if (!$appointment->review) {
            return response()->json(['error' => 'No review found for this appointment.'], 404);
        }

        $reviewData = [
            'review_id' => $appointment->review->id,
            'appointment_id' => $appointment->id,
            'review_msg' => $appointment->review->review_msg,
        ];

        return response()->json([
           'status'=>'success',
           'message'=>'Review retrieved successfully!!',
           'data' => $reviewData
        ],200);
    }
    // end function

    /**
        *
        * View Review for a Doctor's Appointment [Patient]
        *
            * - Fetches the appointment and its associated review for a doctor.
            * - Authorizes the user to view the review of the specified appointment.
            * - If no review is found, returns an error message indicating the absence of a review.
            * - If a review exists, retrieves and returns the review details including the review message and appointment ID.
        *
    */

    public function viewDoctorAppointmentReview($appointmentId){
        $appointment = Appointment::with('review')->find($appointmentId);

        Gate::authorize('viewReview',$appointment);

        if (!$appointment->review) {
            return response()->json(['error' => 'No review found for this appointment.'], 404);
        }

        $reviewData = [
            'review_id' => $appointment->review->id,
            'appointment_id' => $appointment->id,
            'review_msg' => $appointment->review->review_msg,
        ];

        return response()->json([
           'status'=>'success',
           'message'=>'Review retrieved successfully!!',
           'data' => $reviewData
        ],200);
    }
}
