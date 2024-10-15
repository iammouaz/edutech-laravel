<?php

namespace App\Http\Controllers;

use App\Services\SubmissionService;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Http\Requests\StoreMultipleSubmissionsRequest;
use App\Http\Resources\SubmissionResource;
use App\Http\Resources\SubmissionCollection;

class SubmissionController extends Controller
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    /**
     * Get all submissions.
     */
    public function index()
    {
        $submissions = $this->submissionService->getAllSubmissions();

        return new SubmissionCollection($submissions);
    }

    /**
     * Get a single submission by ID.
     */
    public function show($id)
    {
        $submission = $this->submissionService->getSubmissionById($id);

        return new SubmissionResource($submission);
    }

    /**
     * Store a new submission.
     */
    public function store(StoreSubmissionRequest $request)
    {
        $validated = $request->validated();
        $submission = $this->submissionService->createSubmission($validated);

        return new SubmissionResource($submission);
    }

    /**
     * Update an existing submission.
     */
    public function update(UpdateSubmissionRequest $request, $id)
    {
        $validated = $request->validated();
        $submission = $this->submissionService->updateSubmission($id, $validated);

        return new SubmissionResource($submission);
    }

    /**
     * Delete a submission.
     */
    public function destroy($id)
    {
        $this->submissionService->deleteSubmission($id);

        return response()->json(['message' => 'Submission deleted successfully']);
    }

    /**
     * Store multiple submissions.
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
