<?php

namespace App\Http\Controllers;

use App\Mail\SendMessage;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
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
    }
}
