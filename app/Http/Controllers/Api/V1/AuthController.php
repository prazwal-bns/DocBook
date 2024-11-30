<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // public function register(RegisterUserRequest $request){
    //     $data = $request->validated();
    //     $data['password'] = Hash::make($request->password);

    //     $user = User::create($data);
        
    //         // Role-specific logic
    //     if ($request->role === 'doctor') {
    //         Doctor::create([
    //             'user_id' => $user->id,
    //             'specialization_id' => $request->specialization_id,
    //             'status' => 'available',
    //             'bio' => $request->input('bio') ?? null,
    //         ]);
    //     } elseif ($request->role === 'patient') {
    //         Patient::create([
    //             'user_id' => $user->id,
    //             'gender' => $request->input('gender', ''),
    //             'dob' => $request->input('dob') ?? null,
    //         ]);
    //     }

    //     // Event for registration
    //     event(new Registered($user));

    //     $token = $user->createToken('api-token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'User registered Successfully !!',
    //         'user' => $user
    //     ],200);
        
    // }
    // end function

    
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();

        // Use the service to register the user
        $user = $this->userService->registerUser($data);

        // Optionally, generate a token for the user
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
    
    public function login(LoginUserRequest $request){
        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }
    
        // Check if the password matches
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
            
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'User Logged In Successfully !!',
            'token' => $token
        ],200);
    }

    
    // end function


    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out Successfully !!'
        ]);
    }
}
