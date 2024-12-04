<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
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

    public function esewaPaySuccess(Request $request) {


        $esewa = new Esewa();
        $response = $esewa->decode($request);

        // dd($response);

        if ($response) {
            if (isset($response['transaction_uuid'])) {
                $transactionUuid = $response['transaction_uuid'];
                $payment = Payment::where('transaction_id', $transactionUuid)->first();
                if ($payment) {
                    // Update the payment status to 'success' 
                    $payment->update([
                        'payment_status' => 'paid',
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

}
