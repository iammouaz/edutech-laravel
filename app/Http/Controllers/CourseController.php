<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckTeacherRole;
use App\Http\Middleware\CheckStudentRole;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;

class CourseController extends BaseController
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
        $this->middleware(CheckTeacherRole::class)->only(['update', 'store', 'destroy']);
        $this->middleware(CheckStudentRole::class)->only('joinCourse');

    }

    /**
     * Get all courses
     */
    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return new CourseCollection($courses);
    }

    /**
     * Get course by ID
     */
    public function show($id)
    {
        $course = $this->courseService->getCourseById($id);
        return new CourseResource($course);
    }

    /**
     * Create a new course - only for teachers
     */
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        $course = $this->courseService->createCourse($validated);
        return new CourseResource($course);
    }

    /**
     * Update a course - only for teachers
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        $validated = $request->validated();
        $course = $this->courseService->updateCourse($id, $validated);
        return new CourseResource($course);
    }

    /**
     * Delete a course - only for teachers
     */
    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);
        return response()->json(['message' => 'Course deleted successfully']);
    }

    /**
     * Student joins a course
     */
    public function joinCourse($courseId)
    {
        $user = Auth::user();
        return response()->json($this->courseService->joinCourse($user->id, $courseId));
    }
}
