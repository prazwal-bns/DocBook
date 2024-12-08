<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpecializationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/send-message', [HomeController::class, 'sendMessage'])->name('send.message');


Route::middleware(['auth','verified'])->group(function(){
    Route::get('/payment/{appointmentId}/pay', [PaymentController::class, 'esewaPay'])->name('esewaPay');
    
    // Route::get('/payment/success', [PaymentController::class, 'esewaPaySuccess'])->name('payment.success');
    Route::match(['get', 'post'],'/payment/success', [PaymentController::class, 'esewaPaySuccess'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'esewaPayFailure'])->name('payment.failure');

    // Stripe payment
    Route::controller(PaymentController::class)->group(function(){
        Route::get('/stripe/payment/{appointmentId}', 'stripe')->name('stripe.payment');
        Route::post('/stripe/payment', 'stripePost')->name('stripe.post');
    });

});


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
    Route::post('/store/appointment',  [AppointmentController::class, 'storeAppointment'])->name('store.my.appointments');

    Route::get('/view/my/appointment',  [AppointmentController::class, 'viewMyAppointment'])->name('view.my.appointment');

    Route::get('view/appointment/details/{id}', [AppointmentController::class, 'viewMyAppointmentDetails'])->name('view.appointment.details');

    Route::get('edit/myappointment/date/{appointmentId}', [AppointmentController::class, 'editMyAppointmentDate'])->name('edit.myAppoinment.date');

    Route::put('update/my/appointment/{appointmentId}',[AppointmentController::class, 'updateMyAppointment'])->name('update.my.appointment');

    Route::delete('delete/myAppointment/{appointmentId}',[AppointmentController::class, 'deleteMyAppointment'])->name('delete.myAppoinment');

    Route::get('view/doctor/review/{appointmentId}', [ReviewController::class, 'viewDoctorReview'])->name('view.doctor.review');

    Route::get('/payment/{appointmentId}', [PaymentController::class, 'generateToken'])->name('generate.token');

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

    Route::get('/view/doctor/appointments', [AppointmentController::class, 'viewDoctorAppointments'])->name('view.doctor.appointments');

    Route::get('/appointments/{id}/edit', [AppointmentController::class, 'editPatientAppointment'])->name('edit.patient.appointment');

    Route::put('update/patient/appointment/{id}', [AppointmentController::class, 'updatePatientAppointment'])->name('update.patient.appointment');

    Route::get('viewA/doctor/appointment/{id}', [AppointmentController::class, 'viewADoctorAppointment'])->name('view.a.doctor.appointment');

    Route::put('setup/status/{userId}', [DoctorController::class, 'setUpStatus'])->name('setup.status');

    Route::get('give/patient/review/{appointmentId}', [ReviewController::class, 'giveReviewToPatient'])->name('give.patient.review');

    Route::post('store/patient/review/{appointmentId}', [ReviewController::class, 'storeReview'])->name('store.patientReview');

    Route::get('view/patient/review/{appointmentId}', [ReviewController::class, 'viewYourReview'])->name('view.patient.review');


});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
