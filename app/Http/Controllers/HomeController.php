<?php

namespace App\Http\Controllers;

use App\Mail\SendMessage;
use App\Models\Appointment;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function sendMessage(Request $request){
        // $content = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email',
        //     'message' => 'required|string',
        // ]);

        $user = "docbook@info.com";

        $name = $request->name;
        $email = $request->email;
        $contactMessage = $request->message;

        Mail::to($user)->send(new SendMessage($name,$email,$contactMessage));

        return redirect()->back();
    }

    public function generateToken($appointmentId){
        $appointmentId = Crypt::decrypt($appointmentId);
        $appointment = Appointment::findOrFail($appointmentId);

        return view('patient.payments.generate-token', compact('appointment'));
    }
}
