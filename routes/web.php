<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ADMIN WORKFLOW
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // admin -> manage patient data
    Route::get('/view/patients', [AdminController::class, 'viewPatients'])->name('view.patients');
    Route::get('/edit/patient/{id}', [AdminController::class, 'editPatient'])->name('edit.patient');
    Route::put('/admin/update/patientProfile/{id}', [AdminController::class, 'adminUpdatePatientProfile'])->name('admin.update.patientProfile');
    Route::post('/admin/patient/changePassword/{id}', [AdminController::class, 'adminChangePatientPass'])->name('admin.patient.changePassword');
    Route::delete('delete/patient/{patientId}', [AdminController::class, 'deletePatient'])->name('delete.patient');


    // admin-> manage doctors data
    Route::get('/view/doctors', [AdminController::class, 'viewDoctors'])->name('view.doctors');
    Route::get('/edit/doctor/{id}', [AdminController::class, 'editDoctor'])->name('edit.doctor');
    Route::put('/admin/update/doctorProfile/{id}', [AdminController::class, 'adminUpdateDoctorProfile'])->name('admin.update.doctorProfile');
    Route::post('/admin/doctor/changePassword/{id}', [AdminController::class, 'adminChangeDoctorPass'])->name('admin.doctor.changePassword');
    Route::delete('delete/doctor/{doctorId}', [AdminController::class, 'deleteDoctor'])->name('delete.doctor');



});



// PATIENT WORKFLOW
Route::middleware(['auth', 'role.redirect:patient'])->group(function () {
    Route::get('/patient/dashboard', [PatientController::class, 'patientDashboard'])->name('patient.dashboard');
    Route::get('/patient/profile', [PatientController::class, 'patientProfile'])->name('patient.profile');

    Route::put('/patient/update/profile',[PatientController::class,'patientUpdateProfile'])->name('patient.update.profile');

    Route::post('/patient/change/password',[PatientController::class,'patientChangePassword'])->name('patient.change.password');
});



// DOCTOR WORKFLOW
Route::middleware(['auth', 'role.redirect:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'doctorDashboard'])->name('doctor.dashboard');
    Route::get('/doctor/profile', [DoctorController::class, 'doctorProfile'])->name('doctor.profile');

    Route::put('/doctor/update/profile',[DoctorController::class,'doctorUpdateProfile'])->name('doctor.update.profile');
    Route::post('/doctor/change/password',[DoctorController::class,'doctorChangePassword'])->name('doctor.change.password');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
