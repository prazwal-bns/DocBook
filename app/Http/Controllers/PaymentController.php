<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use RemoteMerge\Esewa\Client;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    // pay via e-sewa
    public function esewaPay(Request $request, $appointmentId){
        $payment = Payment::where('appointment_id', $appointmentId)->first();

        $pid = uniqid();
        $amount = $payment->amount;

        $payment->pid = $pid;
        $payment->save();

        $successUrl = route('payment.success', );
        $failureUrl = route('payment.failure', );

        $esewa = new Client([
            'merchant_code' => 'EPAYTEST',
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
        ]);

        $esewa->payment($pid, $amount, 0, 0, 0);
    }

    public function esewaPaySuccess() {
        // echo  "success";
        $pid = $_GET['oid'];
        $refid = $_GET['refId'];
        $amount = $_GET['amt'];

        $payment = Payment::where('pid', $pid)->first();

        $payment->payment_status = 'paid';
        $payment->save();

        if($payment->payment_status == 'paid') {
            return redirect()->route('patient.dashboard')->with( 'payment','Payment Successful');
        }
    }

    public function esewaPayFailure() {
        $pid = $_GET['pid'];

        $payment = Payment::where('pid', $pid)->first();
        // $payment->payment_status = 'unpaid';
        // $payment->save();

        if($payment->payment_status == 'unpaid') {
            return redirect()->route('patient.dashboard')->with( 'payment','Payment Failed');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
