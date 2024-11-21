<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpecializationController;
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


    // Admin -> specialization
    Route::get('/view/specializations', [SpecializationController::class, 'viewSpecializations'])->name('view.specializations');
    Route::get('/add/specialization', [SpecializationController::class, 'addSpecialization'])->name('add.specialization');
    Route::post('/store/specialization', [SpecializationController::class, 'storeSpecialization'])->name('store.specialization');

    Route::get('/edit/specialization/{id}', [SpecializationController::class, 'editSpecialization'])->name('edit.specialization');
    Route::put('/update/specialization/{id}', [SpecializationController::class, 'updateSpecialization'])->name('update.specialization');

    Route::delete('/delete/specialization/{id}', [SpecializationController::class, 'deleteSpecialization'])->name('delete.specialization');

});



// PATIENT WORKFLOW
Route::middleware(['auth', 'role.redirect:patient'])->group(function () {
    Route::get('/patient/dashboard', [PatientController::class, 'patientDashboard'])->name('patient.dashboard');
    Route::get('/patient/profile', [PatientController::class, 'patientProfile'])->name('patient.profile');
    Route::put('/patient/update/profile',[PatientController::class,'patientUpdateProfile'])->name('patient.update.profile');
    Route::post('/patient/change/password',[PatientController::class,'patientChangePassword'])->name('patient.change.password');

    // Appointment
    Route::get('/make/appointment',  [AppointmentController::class, 'makeAppointment'])->name('make.appointment');
    Route::get('/view/doctorsSpecialization/{specializationId}',  [AppointmentController::class, 'viewDoctorsBySpecialization'])->name('view.doctorsBySpecialization');

    Route::get('/book/appointment/{doctorId}',  [AppointmentController::class, 'bookAppointment'])->name('book.appointment');
    Route::post('/store/appointment',  [AppointmentController::class, 'storeAppointment'])->name('appointments.store');

    Route::get('/view/my/appointment',  [AppointmentController::class, 'viewMyAppointment'])->name('view.my.appointment');

});



// DOCTOR WORKFLOW
Route::middleware(['auth', 'role.redirect:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'doctorDashboard'])->name('doctor.dashboard');
    Route::get('/doctor/profile', [DoctorController::class, 'doctorProfile'])->name('doctor.profile');

    Route::put('/doctor/update/profile',[DoctorController::class,'doctorUpdateProfile'])->name('doctor.update.profile');
    Route::post('/doctor/change/password',[DoctorController::class,'doctorChangePassword'])->name('doctor.change.password');

    Route::get('/view/schedule', [ScheduleController::class, 'viewSchedule'])->name('view.schedule');
    Route::get('/add/schedule', [ScheduleController::class, 'addSchedule'])->name('add.schedule');

    Route::post('/store/schedule', [ScheduleController::class, 'storeSchedule'])->name('store.schedule');

    Route::get('/edit/schedule/{id}', [ScheduleController::class, 'editSchedule'])->name('edit.schedule');
    Route::put('/update/schedule/{id}', [ScheduleController::class, 'updateSchedule'])->name('update.schedule');

    Route::delete('/delete/schedule/{doctorId}', [ScheduleController::class, 'deleteSchedule'])->name('delete.schedule');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
