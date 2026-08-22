<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\HomeroomController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\StudentController;

// Auth
Route::get('/',         [AuthController::class,'showLogin'])->name('auth.login');
Route::get('/login',    [AuthController::class,'showLogin'])->name('login');
Route::post('/login',   [AuthController::class,'login']);
Route::post('/logout',  [AuthController::class,'logout'])->name('logout');
Route::get('/dashboard',[AuthController::class,'dashboard'])->middleware('auth')->name('dashboard');

// Admin
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                                [AdminController::class,'dashboard'])->name('dashboard');
    Route::get('/users',                           [AdminController::class,'users'])->name('users');
    Route::get('/users/create',                    [AdminController::class,'createUser'])->name('users.create');
    Route::post('/users',                          [AdminController::class,'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',               [AdminController::class,'editUser'])->name('users.edit');
    Route::put('/users/{user}',                    [AdminController::class,'updateUser'])->name('users.update');
    Route::delete('/users/{user}',                 [AdminController::class,'destroyUser'])->name('users.destroy');
    Route::get('/academic-years',                  [AdminController::class,'academicYears'])->name('academic-years');
    Route::post('/academic-years',                 [AdminController::class,'storeAcademicYear'])->name('academic-years.store');
    Route::match(['POST', 'PATCH', 'GET'], '/academic-years/{year}/activate', [AdminController::class,'activateYear'])->name('academic-years.activate');
    Route::delete('/academic-years/{year}',         [AdminController::class,'destroyAcademicYear'])->name('academic-years.destroy');
    Route::get('/classes',                         [AdminController::class,'classes'])->name('classes');
    Route::post('/classes',                        [AdminController::class,'storeClass'])->name('classes.store');
    Route::delete('/classes/{class}',              [AdminController::class,'destroyClass'])->name('classes.destroy');
    Route::get('/subjects',                        [AdminController::class,'subjects'])->name('subjects');
    Route::post('/subjects',                       [AdminController::class,'storeSubject'])->name('subjects.store');
    Route::delete('/subjects/{subject}',           [AdminController::class,'destroySubject'])->name('subjects.destroy');
    Route::get('/assignments',                     [AdminController::class,'assignments'])->name('assignments');
    Route::post('/assignments',                    [AdminController::class,'storeAssignment'])->name('assignments.store');
    Route::delete('/assignments/{assignment}',     [AdminController::class,'destroyAssignment'])->name('assignments.destroy');
    Route::get('/students',                        [AdminController::class,'students'])->name('students');
    Route::get('/students/{student}/report',        [AdminController::class,'previewReportCard'])->name('students.report');
    Route::post('/students',                       [AdminController::class,'storeStudent'])->name('students.store');
    Route::delete('/students/{student}',           [AdminController::class,'destroyStudent'])->name('students.destroy');
    Route::post('/students/{student}/password',    [AdminController::class,'setStudentPassword'])->name('students.set-password');
    Route::get('/school-settings',                 [AdminController::class,'schoolSettings'])->name('school-settings');
    Route::get('/school-settings/preview',         [AdminController::class,'previewReportCard'])->name('school-settings.preview');
    Route::get('/school-settings/preview/{student}', [AdminController::class,'previewReportCard'])->name('school-settings.preview.student');
    Route::post('/school-settings',                [AdminController::class,'updateSchoolSettings'])->name('school-settings.update');
});

// Teacher
Route::middleware(['auth','role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/',                     [TeacherController::class,'dashboard'])->name('dashboard');
    Route::get('/grades/{assignment}',  [TeacherController::class,'grades'])->name('grades');
    Route::post('/grades/{assignment}', [TeacherController::class,'saveGrades'])->name('grades.store');
});

// Homeroom
Route::middleware(['auth','role:homeroom'])->prefix('homeroom')->name('homeroom.')->group(function () {
    Route::get('/',                     [HomeroomController::class,'dashboard'])->name('dashboard');
    Route::get('/attendance/daily',      [HomeroomController::class,'dailyAttendance'])->name('attendance.daily');
    Route::post('/attendance/daily',     [HomeroomController::class,'saveDailyAttendance'])->name('attendance.daily.store');
    Route::get('/attendance/monthly',    [HomeroomController::class,'monthlyAttendance'])->name('attendance.monthly');
    Route::post('/attendance/sync',      [HomeroomController::class,'syncAttendance'])->name('attendance.sync');
    Route::get('/remarks',              [HomeroomController::class,'remarks'])->name('remarks');
    Route::post('/remarks',             [HomeroomController::class,'saveRemarks'])->name('remarks.store');
    Route::get('/report/{student}',     [HomeroomController::class,'reportCard'])->name('report-card');
});

// Principal
Route::middleware(['auth','role:principal'])->prefix('principal')->name('principal.')->group(function () {
    Route::get('/',                                    [PrincipalController::class,'dashboard'])->name('dashboard');
    Route::get('/class/{class}',                       [PrincipalController::class,'classReport'])->name('class-report');
    Route::get('/class/{class}/report/{student}',      [PrincipalController::class,'reportCard'])->name('report-card');
});

// Student
Route::middleware(['auth','role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/',            [StudentController::class,'dashboard'])->name('dashboard');
    Route::get('/report-card', [StudentController::class,'reportCard'])->name('report-card');
});

