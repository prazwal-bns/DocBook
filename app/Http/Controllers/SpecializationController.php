<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function viewSpecializations(){
        $specializations = Specialization::all();
        return view('admin.specializations.view_specializations', compact('specializations'));
    }
    // end function

    public function addSpecialization(){
        return view('admin.specializations.add_specialization');
    }
    // emd function

    public function storeSpecialization(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|unique:specializations,name'
        ]);

        Specialization::create($validatedData);

        return redirect()->route('view.specializations')
                     ->with('success', 'Specialization added successfully!');
    }
    // end function

    public function deleteSpecialization(Request $request, $id){
        $specialization = Specialization::findOrFail($id);

        // Find all doctors associated with this specialization
        $doctors = Doctor::where('specialization_id', $id)->get();

        foreach ($doctors as $doctor) {
            if ($doctor->user) {
                $doctor->user->delete();
            }
        }
        // Delete the associated doctors (will automatically be deleted by cascade)
        $doctors->each->delete();

        // Delete the specialization
        $specialization->delete();
        return redirect()->route('view.specializations')
                        ->with('success', 'Specialization and associated doctors and users deleted successfully!');
    }
    // end function

    public function editSpecialization($id){
        $specialization = Specialization::findOrFail($id);
        return view('admin.specializations.edit_specialization', compact('specialization'));
    }
    // end function

    public function updateSpecialization(Request $request , $id){
        $validatedData = $request->validate([
            'name' => 'required|unique:specializations,name,' . $id, // Ensure the name is unique except for the current record
        ]);

        $specialization = Specialization::findOrFail($id);
        $specialization->update($validatedData);

        return redirect()->route('view.specializations')->with('success', 'Specialization updated successfully!');
    }
    // end function
}
