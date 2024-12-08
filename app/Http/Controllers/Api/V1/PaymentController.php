<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\PaymentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Stripe\Stripe;


class PaymentController extends Controller
{
    
    // public function payViaStripe(Request $request, $appointmentId)
    // {
    //     $request->validate([
    //         'stripeToken' => 'required|string',
    //         'appointment_id' => 'required|integer',
    //     ]);
    
    //     $stripeToken = $request->input('stripeToken'); 
    //     $appointmentId = $request->input('appointment_id'); 

    //     // Set Stripe secret key
    //     Stripe::setApiKey(config('stripe.stripe_sk'));

    //     try {
    //         $appointment = Appointment::findOrFail($appointmentId);
            
    //         if (!$request->has('stripeToken')) {
    //             return response()->json(['error' => 'No stripe token provided'], 400);
    //         }
    
    //         // Create the charge using the stripe token
    //         $charge = \Stripe\Charge::create([
    //             'source' => $request->stripeToken, 
    //             'description' => 'Payment for Appointment with ' . $appointment->doctor->user->name,
    //             'amount' => 100000, 
    //             'currency' => 'NPR',
    //         ]);
    
    //         $payment = $appointment->payment->update([
    //             'payment_status' => 'paid',
    //             'payment_method' => 'stripe',
    //         ]);
    
    //         $appointment->update([
    //             'status' => 'confirmed',
    //         ]);

    
    //         // Return success response
    //         return response()->json([
    //             'message' => 'Payment Successful',
    //             'appointment' => new AppointmentResource($appointment),
    //             'payment_details' => [
    //                 'payment_method' => $appointment->payment->payment_method,
    //                 'payment_status' => $appointment->payment->payment_status,
    //             ]
    //         ], 200);
    
    //     } catch (\Stripe\Exception\CardException $e) {
    //         return response()->json([
    //             'error' => 'Payment failed: ' . $e->getMessage()
    //         ], 400);
    //     }
    // }

    
/**
 * Pay for an appointment via Stripe.
 *
    * - Validates the Stripe token from the request.
    * - Retrieves the appointment and its associated payment details.
    * - Checks if the payment is already been made if so an error message is returned.
    * - Charges the customer with the respective appointment amount via Stripe using the provided token.
    * - Updates the payment status to 'paid' and sets appointment status to 'confirmed'.
    * - Returns a success message with appointment and payment details.
 *
*/
    public function payViaStripe(Request $request, $appointmentId)
    {
        $validated = $request->validate([
            'stripeToken' => 'required|string',
        ]);

        try {
            $appointment = Appointment::with('payment', 'doctor.user')->findOrFail($appointmentId);

            if (!$appointment->payment) {
                return response()->json(['error' => 'No payment record found for this appointment.'], 400);
            }

            $payment = $appointment->payment;

            // Check if the payment is already paid
            if ($payment->payment_status === 'paid') {
                return response()->json(['error' => 'Payment has already been completed for this appointment.'], 400);
            }

            // Set Stripe secret key
            \Stripe\Stripe::setApiKey(config('stripe.stripe_sk'));

            // Process the payment via Stripe
            $charge = \Stripe\Charge::create([
                'source' => $validated['stripeToken'],
                'description' => 'Payment for Appointment with ' . $appointment->doctor->user->name,
                'amount' => $appointment->payment->amount * 100, // Convert to the smallest currency unit (e.g., paisa for NPR)
                'currency' => 'NPR',
            ]);

            // Update the payment record
            $payment->update([
                'payment_status' => 'paid',
                'payment_method' => 'stripe',
            ]);

            // Update the appointment status
            $appointment->update([
                'status' => 'confirmed',
            ]);

            // Return success response
            return response()->json([
                'message' => 'Payment Successful',
                'appointment' => new AppointmentResource($appointment),
                'payment_details' => new PaymentResource($payment),
            ], 200);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 400);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['error' => 'Invalid payment request: ' . $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

}
