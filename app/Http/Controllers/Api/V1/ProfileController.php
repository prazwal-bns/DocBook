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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
        *
        * Fetch User Profile
        *
            * - Retrieves the profile information of the authenticated user.
            * - Returns the user's profile data wrapped in a resource format.
            * - If successful, returns the user's profile with a success message.
        *
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
        *
        * Update User Profile
        *
            * - Updates the profile information of the authenticated user.
            * - If the user is a patient, updates patient-specific fields like gender and date of birth.
            * - If the user is a doctor, updates doctor-specific fields like specialization, bio, and status.
            * - Returns the updated profile data with a success message.
        *
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
        *
        * Change User Password
        *
            * - Validates the current password to ensure it matches the stored password.
            * - If the current password is incorrect, throws a validation exception with an error message.
            * - If the current password is correct, updates the password to the new one provided.
            * - Returns a success message indicating the password was changed successfully.
        *
    */

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
