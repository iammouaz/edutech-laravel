<?php

namespace App\Http\Controllers;

use App\Services\SubmissionService;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Http\Requests\StoreMultipleSubmissionsRequest;
use App\Http\Resources\SubmissionResource;
use App\Http\Resources\SubmissionCollection;
use App\Http\Middleware\CheckStudentRole;

class SubmissionController extends BaseController
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
        $this->middleware(CheckStudentRole::class)->only(['store', 'storeMultiple']);

    }

    /**
     * Get all submissions.
     *
     * This endpoint retrieves a list of all submissions.
     *
     * @group Submissions
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "assignment_id": 2,
     *       "content": "This is the content of the submission.",
     *       "created_at": "2024-10-16T00:00:00.000000Z",
     *       "updated_at": "2024-10-16T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $submissions = $this->submissionService->getAllSubmissions();

        return new SubmissionCollection($submissions);
    }

    /**
     * Get a single submission by ID.
     *
     * This endpoint retrieves a single submission by its ID.
     *
     * @group Submissions
     * @urlParam id integer required The ID of the submission. Example: 1
     * @response 200 {
     *   "id": 1,
     *   "assignment_id": 2,
     *   "content": "This is the content of the submission.",
     *   "created_at": "2024-10-16T00:00:00.000000Z",
     *   "updated_at": "2024-10-16T00:00:00.000000Z"
     * }
     * @response 404 {
     *   "message": "Submission not found"
     * }
     */
    public function show($id)
    {
        $submission = $this->submissionService->getSubmissionById($id);

        return new SubmissionResource($submission);
    }

    /**
     * Store a new submission.
     *
     * This endpoint allows you to create a new submission.
     *
     * @group Submissions
     * @bodyParam assignment_id integer required The ID of the assignment for which the submission is made. Example: 2
     * @bodyParam content string required The content of the submission. Example: This is my homework submission.
     * @response 201 {
     *   "id": 1,
     *   "assignment_id": 2,
     *   "content": "This is the content of the submission.",
     *   "created_at": "2024-10-16T00:00:00.000000Z",
     *   "updated_at": "2024-10-16T00:00:00.000000Z"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "assignment_id": ["The assignment_id field is required."],
     *     "content": ["The content field is required."]
     *   }
     * }
     */
    public function store(StoreSubmissionRequest $request)
    {
        $validated = $request->validated();
        $submission = $this->submissionService->createSubmission($validated);

        return new SubmissionResource($submission);
    }

    /**
     * Update an existing submission.
     *
     * This endpoint allows you to update an existing submission.
     *
     * @group Submissions
     * @urlParam id integer required The ID of the submission to update. Example: 1
     * @bodyParam assignment_id integer optional The ID of the assignment for the submission. Example: 2
     * @bodyParam content string optional The content of the submission. Example: This is my updated homework submission.
     * @response 200 {
     *   "id": 1,
     *   "assignment_id": 2,
     *   "content": "This is the updated content of the submission.",
     *   "created_at": "2024-10-16T00:00:00.000000Z",
     *   "updated_at": "2024-10-17T00:00:00.000000Z"
     * }
     * @response 404 {
     *   "message": "Submission not found"
     * }
     */
    public function update(UpdateSubmissionRequest $request, $id)
    {
        $validated = $request->validated();
        $submission = $this->submissionService->updateSubmission($id, $validated);

        return new SubmissionResource($submission);
    }

    /**
     * Delete a submission.
     *
     * This endpoint allows you to delete a submission.
     *
     * @group Submissions
     * @urlParam id integer required The ID of the submission to delete. Example: 1
     * @response 200 {
     *   "message": "Submission deleted successfully"
     * }
     * @response 404 {
     *   "message": "Submission not found"
     * }
     */
    public function destroy($id)
    {
        $this->submissionService->deleteSubmission($id);

        return response()->json(['message' => 'Submission deleted successfully']);
    }

    /**
     * Store multiple submissions.
     *
     * This endpoint allows you to submit multiple submissions in one request, limited to 5 at a time.
     *
     * @group Submissions
     * @bodyParam submissions array required An array of submission data. Example: [{"assignment_id": 2, "content": "Submission 1"}, {"assignment_id": 3, "content": "Submission 2"}]
     * @bodyParam submissions.*.assignment_id integer required The ID of the assignment for each submission. Example: 2
     * @bodyParam submissions.*.content string required The content of each submission. Example: This is the submission content.
     * @response 200 {
     *   "message": "Submissions processed successfully",
     *   "db_results": [
     *     {
     *       "id": 1,
     *       "assignment_id": 2,
     *       "content": "Submission 1",
     *       "created_at": "2024-10-16T00:00:00.000000Z",
     *       "updated_at": "2024-10-16T00:00:00.000000Z"
     *     },
     *     {
     *       "id": 2,
     *       "assignment_id": 3,
     *       "content": "Submission 2",
     *       "created_at": "2024-10-16T00:00:00.000000Z",
     *       "updated_at": "2024-10-16T00:00:00.000000Z"
     *     }
     *   ],
     *   "log_results": [
     *     {
     *         "status": "success",
     *         "data": {
     *             "assignment_id": 1,
     *             "content": "Submission 1",
     *             "user_id": 7,
     *             "id": 101
     *         }
     *     },
     *     {
     *         "status": "success",
     *         "data": {
     *             "assignment_id": 1,
     *             "content": "Submission 2",
     *             "user_id": 7,
     *             "id": 101
     *         }
     *     }
     *   ]
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "submissions": ["Submissions array is required."],
     *     "submissions.max": ["You can only submit up to 5 assignments at a time."],
     *     "submissions.*.assignment_id": ["Assignment ID is required for each submission."],
     *     "submissions.*.content": ["Content is required for each submission."]
     *   }
     * }
     */
    public function storeMultiple(StoreMultipleSubmissionsRequest $request)
    {
        $validated = $request->validated();

        $dbResults = $this->submissionService->createMultipleSubmissions($validated['submissions']);
        $logResults = $this->submissionService->logSubmissionsToExternalService($validated['submissions']);

        return response()->json([
            'message' => 'Submissions processed successfully',
            'db_results' => SubmissionResource::collection($dbResults),
            'log_results' => $logResults
        ]);
    }
}
