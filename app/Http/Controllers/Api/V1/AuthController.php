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

    /**
        *
        * Register a New User
        *
            * - Validates the input data using the `RegisterUserRequest` class.
            * - Registers the user using a service method and returns the created user.
            * - Optionally generates an API token for the newly registered user.
            * - Returns a success message along with the user details and the generated token.
        *
    */

    
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

    /**
        *
        * User Login
        *
            * - Validates the input credentials using the `LoginUserRequest` class.
            * - Checks if the user exists in the database by email.
            * - Validates the password by comparing it with the stored hashed password.
            * - If credentials are correct, generates an API token for the user.
            * - If credentials are incorrect, returns a validation error.
            * - Returns a success message along with the generated token.
        *
    */
    
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


    /**
        *
        * User Logout
        *
            * - Revokes all tokens associated with the authenticated user.
            * - Ensures the user is logged out by deleting their active sessions.
            * - Returns a success message upon successful logout.
        *
    */

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out Successfully !!'
        ]);
    }
}
