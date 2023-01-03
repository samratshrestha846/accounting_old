<?php

use App\Http\Controllers\Lab\HospitalDesignationController;
use App\Http\Controllers\Lab\HospitalStaffController;
use App\Http\Controllers\Lab\LabController;
use App\Http\Controllers\Lab\MedicalHistoryController;
use App\Http\Controllers\Lab\AppointmentController;
use App\Http\Controllers\Lab\PatientController;
use App\Http\Controllers\Lab\AppointmentReportController;
use App\Http\Controllers\Lab\TestTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('patients', PatientController::class);
Route::resource('lab', LabController::class);
Route::resource('hospital-designation', HospitalDesignationController::class);
Route::resource('hospital-staff', HospitalStaffController::class);
Route::resource('appointment-report', AppointmentReportController::class);
Route::resource('test-type', TestTypeController::class);


Route::prefix('medical-history')->group(function () {
    Route::post('{patient}', [MedicalHistoryController::class, 'store'])->name('medical-history.store');
    Route::get('{patient}/create', [MedicalHistoryController::class, 'create'])->name('medical-history.create');
    Route::get('{patient}/{medicalHistory}/edit', [MedicalHistoryController::class, 'edit'])->name('medical-history.edit');
    Route::patch('{patient}/{medicalHistory}', [MedicalHistoryController::class, 'update'])->name('medical-history.update');
    Route::delete('{patient}/{medicalHistory}', [MedicalHistoryController::class, 'destroy'])->name('medical-history.destroy');
});
Route::prefix('appointment')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('allAppointments');
    Route::get('/assigned-to-me', [AppointmentController::class, 'assignedToMe'])->name('assignedAppointments');
    Route::post('{patient}', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('{patient}/create', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::get('{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
    Route::patch('{appointment}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('{patient}/{appointment}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');
    Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('appointment.show');

});

