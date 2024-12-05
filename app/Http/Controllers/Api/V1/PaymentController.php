<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Xentixar\EsewaSdk\Esewa;

class PaymentController extends Controller
{
    public function esewaPayApi(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'appointmentId' => 'required|exists:payments,appointment_id',
        ]);

        $appointmentId = $request->appointmentId;

        // Fetch payment record
        $payment = Payment::where('appointment_id', $appointmentId)->firstOrFail();

        // Generate unique transaction ID
        $transaction_id = 'TXN-' . uniqid();
        $payment->update(['transaction_id' => $transaction_id]);

        // Initialize Esewa
        $esewa = new Esewa();
        $esewa->config(
            route('payment.success'), // URL for success
            route('payment.failure'), // URL for failure
            $payment->amount,
            $transaction_id
        );

        // Get Esewa initialization URL or details
        // $paymentUrl = $esewa->init();

        return response()->json([
            'success' => true,
            'message' => 'Esewa payment initialized.',
            'payment_url' => $esewa->init(),
        ], 200);
    }

    // public function esewaPayApi($appointmentId)
    // {
    //     // Retrieve the payment record
    //     $payment = Payment::where('appointment_id', $appointmentId)->first();

    //     if (!$payment) {
    //         return response()->json(['error' => 'Payment record not found!'], 404);
    //     }

    //     // Generate a new transaction ID
    //     $transaction_id = 'TXN-' . uniqid();
    //     $payment->update(['transaction_id' => $transaction_id]);

    //     // eSewa v2 API URL
    //     $esewaUrl = "https://rc-epay.esewa.com.np/auth";

    //     // Prepare the payment data
    //     $paymentData = [
    //         'amount' => $payment->amount,           // Payment amount
    //         'merchantCode' => 'testmerchant',      // Your merchant code (sandbox or production)
    //         'transactionId' => $transaction_id,   // Unique transaction ID
    //         'successUrl' => route('payment.success'),  // Success URL
    //         'failureUrl' => route('payment.failure'),  // Failure URL
    //     ];

    //     try {
    //         // Initialize Guzzle client
    //         $client = new \GuzzleHttp\Client();

    //         // Make a POST request to eSewa v2 API
    //         $response = $client->post($esewaUrl, [
    //             'json' => $paymentData, // Send data as JSON
    //             'headers' => [
    //                 'Content-Type' => 'application/json',
    //             ],
    //         ]);

    //         // Parse the response
    //         $responseBody = json_decode($response->getBody(), true);

    //         if (isset($responseBody['redirectUrl'])) {
    //             // Return the eSewa payment URL for redirection
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Please proceed to eSewa for payment.',
    //                 'payment_url' => $responseBody['redirectUrl'],
    //             ]);
    //         }

    //         // Handle case where the redirect URL is not returned
    //         return response()->json([
    //             'error' => 'Failed to initiate payment. Please try again later.',
    //         ], 500);
    //     } catch (\Exception $e) {
    //         // Log the exception for debugging
    //         \Log::error('eSewa Payment Error', ['error' => $e->getMessage()]);

    //         // Return an error response
    //         return response()->json([
    //             'error' => 'Something went wrong. Please try again later.',
    //         ], 500);
    //     }
    // }

    
    // end function

    public function esewaPaySuccessApi(Request $request)
{
    // Decode Esewa response
    $esewa = new Esewa();
    $response = $esewa->decode();

    // Convert the JsonResponse to an array using getData()
    $responseData = $response->getData(true);  // true to return as array

    // Check if the response contains 'transaction_uuid'
    if (!$responseData) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid response from Esewa.',
        ], 400);
    }

    if (!isset($responseData['transaction_uuid'])) {
        return response()->json([
            'success' => false,
            'message' => 'Missing transaction UUID in response.',
        ], 400);
    }

    // Validate and process payment
    $transactionUuid = $responseData['transaction_uuid'];
    $payment = Payment::where('transaction_id', $transactionUuid)->first();

    if (!$payment) {
        return response()->json([
            'success' => false,
            'message' => 'Payment record not found.',
        ], 404);
    }

    // Update payment and appointment statuses
    $payment->update(['payment_status' => 'paid']);
    $payment->appointment->update(['status' => 'confirmed']);

    return response()->json([
        'success' => true,
        'message' => 'Payment successful!',
    ], 200);
}




    // end function

    public function esewaPayFailureApi(Request $request)
    {
        // Log failure response for debugging
        \Log::info('eSewa Failure Response', $request->all());
    
        return response()->json(['error' => 'Payment failed'], 400);
    }
    
    
    
}
