<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProfileController as UserProfileController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\SpecializationController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('V1')->group(function(){

    // Public Routes for Register, Login and Logout
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);


    // User Update their Specific Profile
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/profile', [UserProfileController::class, 'show']);
        Route::put('/profile', [UserProfileController::class, 'update']);
        Route::put('/changePassword', [UserProfileController::class, 'changePassword']);
    });


    Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function(){
        Route::apiResource('users', UserController::class);
        Route::apiResource('specializations', SpecializationController::class);
    });

    // Doctor Routes
    Route::prefix('doctor')->middleware(['auth:sanctum','role.redirect:doctor'])->group(function(){
        Route::apiResource('schedules', ScheduleController::class)->except(['destroy']);
        Route::delete('schedules', [ScheduleController::class, 'destroy']);

        Route::get('/view/my/appointments', [AppointmentController::class, 'viewAllAppointments'])->name('view.all.appointments');
        Route::put('update/patient-apt-status/{appointmentId}', [AppointmentController::class, 'updateAppointmentStatus'])->name('update.appointment.status');

    });


    // Patient Routes
    Route::prefix('patient')->middleware(['auth:sanctum','role.redirect:patient'])->group(function(){
        Route::apiResource('appointments',AppointmentController::class);
        // Route::apiResource('specializations',SpecializationController::class)->only(['index']);

        Route::get('view/all/specializations', [SpecializationController::class, 'viewAllSpecializations'])->name('view.all.specializations');

        Route::get('specialized/doctors/{specId}',[SpecializationController::class, 'viewAssociatedDoctors'])->name('view.specialized.doctors');
        
        Route::get('view/doctor/schedule/{doctorId}',[ScheduleController::class, 'viewWeeklySchedules'])->name('view.doctors.schedule');


        // E-Sewa Payment
        Route::match(['get', 'post'],'/payment/{appointmentId}/pay', [PaymentController::class, 'esewaPayApi'])->name('api.esewaPay');
        
        Route::match(['get', 'post'], '/payment/success', [PaymentController::class, 'esewaPaySuccessApi'])->name('api.payment.success');

        Route::match(['get', 'post'], '/payment/failure', [PaymentController::class, 'esewaPayFailureApi'])->name('api.payment.failure');
    });

});