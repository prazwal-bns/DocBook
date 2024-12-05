<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\SpecializationResource;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
        *
        * Retrieve All Specializations [Admin]
        *
            * - Fetches all specializations from the database.
            * - Returns a success response with the list of specializations.
            * - If there are no specializations, it returns an empty list.
        *
    */

    public function index()
    {
        $specializations = Specialization::all();
        return response()->json([
            'message' => 'Data Fetched Successfully !!',
            'data' => SpecializationResource::collection($specializations)
        ],200);
    }

    /**
        *
        * Retrieve All Specializations for [Patient]
        *
            * - Fetches all specializations from the database.
            * - Returns a success response with the list of specializations.
            * - If there are no specializations, it returns an empty list.
        *
    */

    public function viewAllSpecializations(){
        $specializations = Specialization::all();
        return response()->json([
            'message' => 'Data Fetched Successfully !!',
            'data' => SpecializationResource::collection($specializations)
        ],200);
    }

   /**
        *
        * Store New Specialization - [Admin]
        *
            * - Validates and stores a new specialization in the database.
            * - Ensures that the specialization name is provided and is a string of a maximum length of 255 characters.
            * - Returns a success response with the created specialization data.
        *
    */

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        
        $specialization = Specialization::create($data);

        return response()->json([
            'message' => 'Specialization Added Successfully !!',
            'data' => new SpecializationResource($specialization)
        ],201);
    }

    /**
        *
        * Retrieve a particular Specialization data via ID - [Admin]
        *
            * - Retrieves a specialization by its ID.
            * - If the specialization is found, returns a success response with the specialization data.
            * - If the specialization is not found, catches the exception and returns a 404 error with an appropriate message.
        *
    */

    public function show(string $id)
    {
        try {
            $specialization = Specialization::findOrFail($id);

            return response()->json([
                'message' => 'Specialization Retrieved Successfully !!',
                'data' => new SpecializationResource($specialization)
            ],200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Specialization with the mentioned id not found!!'
            ], 404);
        }
    }

    /**
        *
        * Update a particular Specialization data via ID - [Admin]
        *
            * - Updates the name of the specialization with the given ID.
            * - If the specialization is found, it will be updated with the new data.
            * - If the specialization is not found, the exception is caught and a 404 error is returned.
            * - Requires the `name` field to be provided in the request.
        *
    */

    public function update(Request $request, string $id)
    {
        $data = $request->validate(['name'=>'required']);
        try {
            $specialization = Specialization::findOrFail($id);
            
            $specialization->update($data);

            return response()->json([
                'message' => 'Specialization Updated Successfully !!',
                'data' => new SpecializationResource($specialization)
            ],200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Specialization with the mentioned id not found!!'
            ], 404);
        }
    }

    /**
        *
        * Delete Specialization and Related Doctors - [Admin]
        *
            * - Deletes the specialization along with all related doctors and their user accounts.
            * - If the specialization with the provided ID is found, the associated doctors and their user accounts are also deleted.
            * - If the specialization is not found, a 404 error is returned.
            * - Ensures that all data linked to the specialization (doctors and user accounts) are removed.
        *
    */

    public function destroy(string $id)
    {
        try {
            $specialization = Specialization::findOrFail($id);
    
            $doctors = Doctor::where('specialization_id', $specialization->id)->get();
    
            foreach ($doctors as $doctor) {
                $doctor->user()->delete();
                $doctor->delete();
            }
    
            // Delete the specialization
            $specialization->delete();
    
            return response()->json([
                'message' => 'Specialization and related doctors (including user accounts) deleted successfully!'
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Specialization with the mentioned id not found!!'
            ], 404);
        }
    }
    // end function

    /**
        *
        * View Doctors Associated with a Specialization - [Patient]
        *
            * - Retrieves doctors associated with a given specialization ID.
            * - Filters doctors based on their availability and having schedules.
            * - If no doctors are available or the specialization does not exist, appropriate messages are returned.
            * - If doctors are available, their information and the specialization details are returned successfully.
        *
    */
    public function viewAssociatedDoctors($specializationId){
        $doctors = Doctor::where('specialization_id', $specializationId)
            ->whereHas('schedules')
            ->where('status', 'available')
            ->get();

        $specialization = Specialization::find($specializationId);

        if (!$specialization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Specialization not found.',
            ], 404);
        }
    
        if ($doctors->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No doctors available for this specialization.',
                'data' => [],
            ], 200);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Doctors retrieved successfully.',
            'data' => [
                'specialization' => new SpecializationResource($specialization),
                'doctors' => DoctorResource::collection($doctors),
            ],
        ], 200);
    }
    

}
