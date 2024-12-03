<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user(); // Get the logged-in user
        return response()->json([
            'message' => 'Profile fetched successfully!',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user(); // Get the logged-in user
        $data = $request->validated(); // Get validated data

        $user->update($data); // Update the user data

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
            'message' => 'Profile updated successfully!',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse{
        $data = $request->validated();

        $user = auth()->user();

        if(!Hash::check($data['current_password'], $user->password)){
            throw ValidationException::withMessages([
                'current_password' => ['The provided current password does not match our records.'],
            ]);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully!',
        ], 200);
    }
    // end function
}
