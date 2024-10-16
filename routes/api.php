<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckTeacherRole;
use App\Http\Middleware\CheckStudentRole;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // Disabled Not required, if I miss that it's required, Just uncomment it :)
    Route::apiResource('users', UserController::class);

    Route::apiResource('assignments', AssignmentController::class);

    Route::apiResource('courses', CourseController::class);
    Route::post('join-course/{courseId}', [CourseController::class, 'joinCourse']);

    Route::apiResource('submissions', SubmissionController::class);
    Route::post('submissions/multiple', [SubmissionController::class, 'storeMultiple']);

});
