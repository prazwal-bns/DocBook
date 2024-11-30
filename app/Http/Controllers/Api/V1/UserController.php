<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('patient','doctor')->get();

        return response()->json([
            'message' => 'Data Fetched Successfully !!',
            'data' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request)
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('patient','doctor')->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        return response()->json([
            'message' => 'User Data Fetched Successfully !!',
            'data' => $user
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        $data = $request->validated();

        // Update the user
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // If user is a patient, update patient-specific fields
        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
            if ($patient) {
                $patient->update($request->only(['gender', 'dob']));
            }
        }

        // If user is a doctor, update doctor-specific fields
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if ($doctor) {
                $doctor->update($request->only(['specialization_id', 'bio', 'status']));
            }
        }

        return response()->json([
            'message' => 'User profile updated successfully!',
            'data' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'patient') {
            $user->patient()->delete();
        }

        if ($user->role === 'doctor') {
            $user->doctor()->delete();
        }

        $user->delete();

        return response()->json([
            'message' => 'User and related records deleted successfully!'
        ], 200);
    }

}
