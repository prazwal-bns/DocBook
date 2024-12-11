<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Crypt;
use Xentixar\EsewaSdk\Esewa;

class PaymentController extends Controller
{

    // pay via e-sewa
    public function esewaPay($appointmentId){
        $payment = Payment::where('appointment_id', $appointmentId)->first();

        $payment = Payment::findOrFail($payment->id);

        // dd($payment->toArray());

        $esewa = new Esewa();

        $transaction_id = 'TXN-' . uniqid();
        $payment->update(['transaction_id' => $transaction_id]);

        $esewa->config(
            route('payment.success'),
            route('payment.failure'),
            $payment->amount,
            $transaction_id
        );

        return $esewa->init();
    }

    public function esewaPaySuccess() {
        $esewa = new Esewa();
        $response = $esewa->decode();

        // dd($response);

        if ($response) {
            if (isset($response['transaction_uuid'])) {
                $transactionUuid = $response['transaction_uuid'];
                $payment = Payment::where('transaction_id', $transactionUuid)->first();
                if ($payment) {
                    // Update the payment status to 'success' 
                    $payment->update([
                        'payment_status' => 'paid',
                        'payment_method' => 'esewa'
                    ]);

                    // also update appointment status to confirmed
                    $payment->appointment->update([
                        'status' => 'confirmed'
                    ]);

                    return redirect()->route('view.my.appointment')->with('success', 'Payment successful!');
                }

                return redirect()->route('view.my.appointment')->with('error', 'Payment record not found!');
            }

            return redirect()->route('view.my.appointment')->with('error', 'Invalid response from eSewa!');
        }
    }

    public function esewaPayFailure(Request $request) {
        return redirect()->route('patient.dashboard')->with( 'error','Payment Failed');
    }

    // Stripe Payment Intergration


    public function stripe($encryptedId)
    {
        try {
            // $appointmentId = Crypt::decrypt($encryptedId);
            return view('patient.payments.stripe', compact('encryptedId'));
        } catch (\Exception $e) {
            abort(403, 'Invalid or expired appointment ID.');
        }
    }

    public function stripePost(Request $request) {
        // Set Stripe API key
        // dd(env('STRIPE_SECRET'));
        Stripe::setApiKey(config('stripe.stripe_sk'));
      
        
        try {
            $appointmentId = Crypt::decrypt($request->appointment_id);
            $appointment = Appointment::findOrFail($appointmentId);

            
            $charge = \Stripe\Charge::create([
                'source' => $request->stripeToken,
                'description' => 'Payment for Appointment with '. $appointment->doctor->user->name,
                'amount' => 100000,  // Amount in cents (e.g., $500.00)
                'currency' => 'NPR',
            ]);

            $appointment->payment->update([
                'payment_status' => 'paid',
                'payment_method' => 'stripe'
            ]);

            $appointment->update([
                'status' => 'confirmed'
            ]);
    
            return redirect()->route('view.my.appointment')->with('success', 'Payment was successful!');
        } catch (CardException $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
    // end function

    public function generateToken($appointmentId)
    {
        $appointmentId = Crypt::decrypt($appointmentId);  
        $appointment = Appointment::findOrFail($appointmentId);  

        return view('patient.payments.generate-token', compact('appointment'));
    }


    
}
