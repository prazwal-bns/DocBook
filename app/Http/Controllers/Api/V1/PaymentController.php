<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    
    public function payViaStripe(Request $request, $appointmentId)
    {
        $request->validate([
            'stripeToken' => 'required|string',
            'appointment_id' => 'required|integer',
        ]);
    
        $stripeToken = $request->input('stripeToken'); 
        $appointmentId = $request->input('appointment_id'); 

        // Set Stripe secret key
        Stripe::setApiKey(config('stripe.stripe_sk'));

        try {
            $appointment = Appointment::findOrFail($appointmentId);
            
            if (!$request->has('stripeToken')) {
                return response()->json(['error' => 'No stripe token provided'], 400);
            }
    
            // Create the charge using the stripe token
            $charge = \Stripe\Charge::create([
                'source' => $request->stripeToken, 
                'description' => 'Payment for Appointment with ' . $appointment->doctor->user->name,
                'amount' => 100000, 
                'currency' => 'NPR',
            ]);
    
            $payment = $appointment->payment->update([
                'payment_status' => 'paid',
                'payment_method' => 'stripe',
            ]);
    
            $appointment->update([
                'status' => 'confirmed',
            ]);

    
            // Return success response
            return response()->json([
                'message' => 'Payment Successful',
                'appointment' => new AppointmentResource($appointment),
                'payment_details' => [
                    'payment_method' => $appointment->payment->payment_method,
                    'payment_status' => $appointment->payment->payment_status,
                ]
            ], 200);
    
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'error' => 'Payment failed: ' . $e->getMessage()
            ], 400);
        }
    }

}
