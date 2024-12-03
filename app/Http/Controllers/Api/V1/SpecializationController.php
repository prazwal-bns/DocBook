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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
