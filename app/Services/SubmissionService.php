<?php

namespace App\Services;

use App\Repositories\Interfaces\SubmissionRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SubmissionService
{
    protected $submissionRepository;

    /** @var Client */
    public $client;

    public function __construct(SubmissionRepositoryInterface $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
        // Initialize Guzzle HTTP Client with base URI for JSONPlaceholder
        $this->client = new Client(['base_uri' => 'https://jsonplaceholder.typicode.com/']);
    }

    // Setter for the client (for testing purposes)
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function getAllSubmissions()
    {
        return $this->submissionRepository->all();
    }

    public function getSubmissionById($id)
    {
        return $this->submissionRepository->find($id);
    }

    public function createSubmission(array $data)
    {
        $data['user_id'] = Auth::id();
        return $this->submissionRepository->create($data);
    }

    public function updateSubmission($id, array $data)
    {
        return $this->submissionRepository->update($id, $data);
    }

    public function deleteSubmission($id)
    {
        return $this->submissionRepository->delete($id);
    }
    /**
     * Log multiple assignment submissions asynchronously to JSONPlaceholder
     *
     * @param array $submissions
     * @return array
     */
    public function logSubmissionsToExternalService(array $submissions): array
    {
        $promises = [];

        foreach ($submissions as $submission) {
            $promises[] = $this->client->postAsync('/posts', [
                'json' => [
                    'assignment_id' => $submission['assignment_id'],
                    'submitted_at' => now(),
                    'student_id' => Auth::id(),
                ]
            ]);
        }

        // Wait for all promises to complete asynchronously using Utils::settle
        $results = Utils::settle($promises)->wait();

        $responses = [];
        foreach ($results as $result) {
            if ($result['state'] === 'fulfilled') {
                $response = $result['value'];
                $data = json_decode($response->getBody(), true);

                Log::info('Submission logged to external service', $data);

                $responses[] = [
                    'status' => 'success',
                    'data' => $data
                ];
            } else {
                Log::error('Failed to log submission to external service', ['error' => $result['reason']]);

                $responses[] = [
                    'status' => 'error',
                    'message' => 'Failed to log submission',
                    'error' => $result['reason']->getMessage()
                ];
            }
        }

        return $responses;
    }

    /**
     * Create multiple submissions.
     *
     * @param array $submissions
     * @return array
     */
    public function createMultipleSubmissions(array $submissions): array
    {
        $results = [];

        foreach ($submissions as $submission) {
            $submission['user_id'] = Auth::id();
            $results[] = $this->submissionRepository->create($submission);
        }

        return $results;
    }
}
