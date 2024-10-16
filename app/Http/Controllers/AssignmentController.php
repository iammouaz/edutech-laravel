<?php

namespace App\Http\Controllers;

use App\Services\AssignmentService;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\AssignmentCollection;
use App\Http\Middleware\CheckTeacherRole;
use Illuminate\Routing\Controller as BaseController;

class AssignmentController extends BaseController
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
        $this->middleware(CheckTeacherRole::class)->only(['update', 'store', 'destroy']);

    }

    /**
     * Display a listing of the assignments.
     *
     * This endpoint retrieves all assignments.
     *
     * @group Assignments
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "First Assignment",
     *       "description": "Complete the following tasks...",
     *       "course_id": 2,
     *       "due_date": "2024-10-16"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $assignments = $this->assignmentService->getAllAssignments();

        return new AssignmentCollection($assignments);
    }

    /**
     * Display the specified assignment.
     *
     * This endpoint retrieves a single assignment by its ID.
     *
     * @group Assignments
     * @urlParam id integer required The ID of the assignment. Example: 1
     * @response 200 {
     *   "id": 1,
     *   "title": "First Assignment",
     *   "description": "Complete the following tasks...",
     *   "course_id": 2,
     *   "due_date": "2024-10-16"
     * }
     * @response 404 {
     *   "message": "Assignment not found"
     * }
     */
    public function show($id)
    {
        $assignment = $this->assignmentService->getAssignmentById($id);

        return new AssignmentResource($assignment);
    }

    /**
     * Store a newly created assignment.
     *
     * This endpoint allows you to create a new assignment.
     *
     * @group Assignments
     * @bodyParam title string required The title of the assignment. Example: Homework #1
     * @bodyParam description string optional The description of the assignment. Example: Solve problems 1-10 from the textbook.
     * @bodyParam course_id integer required The ID of the course to which this assignment belongs. Example: 2
     * @bodyParam due_date date required The due date of the assignment, which must be a date after today. Example: 2024-10-20
     * @response 201 {
     *   "id": 1,
     *   "title": "First Assignment",
     *   "description": "Complete the following tasks...",
     *   "course_id": 2,
     *   "due_date": "2024-10-20"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "title": ["The title field is required."],
     *     "course_id": ["The course_id field is required."],
     *     "due_date": ["The due_date must be a date after today."]
     *   }
     * }
     */
    public function store(StoreAssignmentRequest $request)
    {
        $validated = $request->validated();
        $assignment = $this->assignmentService->createAssignment($validated);

        return new AssignmentResource($assignment);
    }

    /**
     * Update the specified assignment.
     *
     * This endpoint allows you to update an existing assignment.
     *
     * @group Assignments
     * @urlParam id integer required The ID of the assignment to update. Example: 1
     * @bodyParam title string optional The updated title of the assignment. Example: Updated Homework #1
     * @bodyParam description string optional The updated description of the assignment. Example: Solve problems 1-15 from the textbook.
     * @bodyParam course_id integer optional The ID of the course to which this assignment belongs. Example: 2
     * @bodyParam due_date date optional The updated due date of the assignment, which must be a date after today. Example: 2024-10-21
     * @response 200 {
     *   "id": 1,
     *   "title": "Updated Homework #1",
     *   "description": "Solve problems 1-15 from the textbook.",
     *   "course_id": 2,
     *   "due_date": "2024-10-21"
     * }
     * @response 404 {
     *   "message": "Assignment not found"
     * }
     */
    public function update(UpdateAssignmentRequest $request, $id)
    {
        $validated = $request->validated();
        $assignment = $this->assignmentService->updateAssignment($id, $validated);

        return new AssignmentResource($assignment);
    }

    /**
     * Remove the specified assignment from storage.
     *
     * This endpoint allows you to delete an assignment.
     *
     * @group Assignments
     * @urlParam id integer required The ID of the assignment to delete. Example: 1
     * @response 200 {
     *   "message": "Assignment deleted successfully"
     * }
     * @response 404 {
     *   "message": "Assignment not found"
     * }
     */
    public function destroy($id)
    {
        $this->assignmentService->deleteAssignment($id);

        return response()->json(['message' => 'Assignment deleted successfully']);
    }
}
