<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    // public function index()
    // {
    //     $users = User::with('patient','doctor')->get();

    //     return response()->json([
    //         'message' => 'Data Fetched Successfully !!',
    //         'data' =>  UserResource::collection($users)
    //     ], 200);
    // }

    /**
        *
        * Fetch Users with their respective Patient and Doctor Data [Admin]
        *
            * - Retrieves users along with their associated patient and doctor data.
            * - Returns paginated results, including metadata like total count, current page, and page limits.
            * - If data is available, it is returned successfully along with pagination details.
        *
    */

    public function index()
    {
        $users = User::with('patient', 'doctor')->paginate(2); 

        return response()->json([
            'message' => 'Data Fetched Successfully !!',
            'data' => UserResource::collection($users->items()), 
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ], 200);
    }


   /**
        *
        * Register a New User and Generate an API Token [Admin]
        *
            * - Validates the incoming request data for user registration.
            * - Calls the user service to register the user with the provided data.
            * - Generates an API token for the newly registered user.
            * - Returns a response with the user data and the generated token.
        *
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
        *
        * Retrieve User Data by ID [Admin]
        *
            * - Finds the user with the specified ID and includes related patient and doctor data.
            * - If the user is not found, returns a 404 error response.
            * - Returns the user data wrapped in a resource if found.
        *
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
            'data' => new UserResource($user)
        ],200);
    }

    /**
        *
        * Update User Profile Data [Admin]
        *
            * - Validates and updates the user's profile data, including optional password change.
            * - If the user is a patient, updates the patient-specific fields like gender and date of birth.
            * - If the user is a doctor, updates doctor-specific fields like specialization, bio, and status.
            * - Returns a success message along with the updated user data.
        *
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
        *
        * Delete User and Related Records [Admin]
        *
            * - Attempts to find and delete a user based on the provided ID.
            * - If the user has a 'patient' role, deletes the associated patient record.
            * - If the user has a 'doctor' role, deletes the associated doctor record.
            * - If the user is found, deletes the user and returns a success message.
            * - Handles exceptions for user not found and any other errors that occur during deletion.
        *
    */

    public function destroy($id)
    {
        try {
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
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'The user with the specified ID does not exist.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to delete the user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
