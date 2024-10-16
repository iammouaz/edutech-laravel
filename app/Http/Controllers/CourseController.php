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
     * Get all courses.
     *
     * This endpoint retrieves a list of all available courses.
     *
     * @group Courses
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Introduction to Programming",
     *       "description": "Learn the basics of programming",
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return new CourseCollection($courses);
    }

    /**
     * Get course by ID.
     *
     * This endpoint retrieves the details of a specific course by its ID.
     *
     * @group Courses
     * @urlParam id integer required The ID of the course. Example: 1
     * @response 200 {
     *   "id": 1,
     *   "title": "Introduction to Programming",
     *   "description": "Learn the basics of programming",
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-01T00:00:00.000000Z"
     * }
     * @response 404 {
     *   "message": "Course not found"
     * }
     */
    public function show($id)
    {
        $course = $this->courseService->getCourseById($id);
        return new CourseResource($course);
    }

    /**
     * Create a new course - only for teachers.
     *
     * This endpoint allows a teacher to create a new course.
     *
     * @group Courses
     * @bodyParam title string required The title of the course. Example: Introduction to Programming
     * @bodyParam description string required The description of the course. Example: Learn the basics of programming.
     * @response 201 {
     *   "id": 1,
     *   "title": "Introduction to Programming",
     *   "description": "Learn the basics of programming",
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-01T00:00:00.000000Z"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "title": ["The title field is required."]
     *   }
     * }
     */
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        $course = $this->courseService->createCourse($validated);
        return new CourseResource($course);
    }

    /**
     * Update a course - only for teachers.
     *
     * This endpoint allows a teacher to update an existing course.
     *
     * @group Courses
     * @urlParam id integer required The ID of the course to update. Example: 1
     * @bodyParam title string optional The updated title of the course. Example: Advanced Programming
     * @bodyParam description string optional The updated description of the course. Example: Learn advanced programming techniques.
     * @response 200 {
     *   "id": 1,
     *   "title": "Advanced Programming",
     *   "description": "Learn advanced programming techniques",
     *   "created_at": "2024-01-01T00:00:00.000000Z",
     *   "updated_at": "2024-01-02T00:00:00.000000Z"
     * }
     * @response 404 {
     *   "message": "Course not found"
     * }
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        $validated = $request->validated();
        $course = $this->courseService->updateCourse($id, $validated);
        return new CourseResource($course);
    }

    /**
     * Delete a course - only for teachers.
     *
     * This endpoint allows a teacher to delete a course.
     *
     * @group Courses
     * @urlParam id integer required The ID of the course to delete. Example: 1
     * @response 200 {
     *   "message": "Course deleted successfully"
     * }
     * @response 404 {
     *   "message": "Course not found"
     * }
     */
    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);
        return response()->json(['message' => 'Course deleted successfully']);
    }

    /**
     * Student joins a course.
     *
     * This endpoint allows a student to join a course.
     *
     * @group Courses
     * @urlParam courseId integer required The ID of the course to join. Example: 1
     * @response 200 {
     *   "message": "Successfully joined the course"
     * }
     * @response 404 {
     *   "message": "Course not found"
     * }
     */
    public function joinCourse($courseId)
    {
        $user = Auth::user();
        return response()->json($this->courseService->joinCourse($user->id, $courseId));
    }
}
