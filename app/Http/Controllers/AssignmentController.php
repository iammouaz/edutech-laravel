<?php

namespace App\Http\Controllers;

use App\Services\AssignmentService;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\AssignmentCollection;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display a listing of the assignments.
     */
    public function index()
    {
        $assignments = $this->assignmentService->getAllAssignments();

        // Return a collection of assignments
        return new AssignmentCollection($assignments);
    }

    /**
     * Display the specified assignment.
     */
    public function show($id)
    {
        $assignment = $this->assignmentService->getAssignmentById($id);

        // Return a single assignment resource
        return new AssignmentResource($assignment);
    }

    /**
     * Store a newly created assignment.
     */
    public function store(StoreAssignmentRequest $request)
    {
        $validated = $request->validated();
        $assignment = $this->assignmentService->createAssignment($validated);

        // Return the created assignment resource
        return new AssignmentResource($assignment);
    }

    /**
     * Update the specified assignment.
     */
    public function update(UpdateAssignmentRequest $request, $id)
    {
        $validated = $request->validated();
        $assignment = $this->assignmentService->updateAssignment($id, $validated);

        // Return the updated assignment resource
        return new AssignmentResource($assignment);
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy($id)
    {
        $this->assignmentService->deleteAssignment($id);

        return response()->json(['message' => 'Assignment deleted successfully']);
    }
}
