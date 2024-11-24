<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function giveReviewToPatient($appointmentId){
        $appointment = Appointment::findOrFail($appointmentId);

        $doctor = Auth::user()->doctor;

        // Check if the authenticated user is the doctor associated with the appointment
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'You are not authorized to add a review for this appointment.');
        }

        if($appointment->status !== 'completed'){
            abort(403, 'The appointment status must be completed to give a review.');
        }

        if ($appointment->review()->exists()) {
            abort(403, 'The appointment already contains a review, you can\'t give another review');
        }

        return view('doctor.reviews.give_patient_review', compact('appointment'));
    }
    // end function

    public function storeReview(Request $request, $appointmentId){
        $validatedData = $request->validate([
            'review_msg' => 'required|string'
        ]);

        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Only completed appointments can have reviews.');
        }

        if ($appointment->review()->exists()) {
            abort(403, 'The appointment already contains a review, you can\'t give another review');
        }

        $review = new Review();
        $review->appointment_id = $appointmentId;
        $review->review_msg = $validatedData['review_msg'];
        $review->save();

        return redirect()->route('view.doctor.appointments')->with('success','Review added successfully !!');

    }
    // end function

    public function viewYourReview($appointmentId){
        $appointment = Appointment::findOrFail($appointmentId);


        return view('doctor.reviews.view_my_review', compact('appointment'));
    }
    // end function

    public function viewDoctorReview($appointmentId){
        $appointment = Appointment::findOrFail($appointmentId);

        return view('patient.reviews.view_my_review', compact('appointment'));
    }
    // end function
}
