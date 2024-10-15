<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SubmissionService;
use App\Repositories\Interfaces\SubmissionRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Mockery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubmissionServiceTest extends TestCase
{
    protected $submissionService;

    /** @var \App\Repositories\Interfaces\SubmissionRepositoryInterface|\Mockery\MockInterface */
    protected $submissionRepositoryMock;

    /** @var \GuzzleHttp\Client|\Mockery\MockInterface */
    protected $clientMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->submissionRepositoryMock = Mockery::mock(SubmissionRepositoryInterface::class);

        $this->submissionService = new \App\Services\SubmissionService($this->submissionRepositoryMock);

        $this->clientMock = Mockery::mock(Client::class);
        $this->submissionService->client = $this->clientMock;
        Auth::shouldReceive('id')->andReturn(1);
    }

    /** @test */
    public function it_can_log_submissions_to_external_service_successfully()
    {
        $submissions = [
            ['assignment_id' => 1, 'content' => 'Submission 1'],
            ['assignment_id' => 2, 'content' => 'Submission 2']
        ];

        $mockResponse = new Response(200, [], json_encode(['id' => 1, 'title' => 'Test Post']));

        $this->clientMock->shouldReceive('postAsync')
            ->twice() // Two submissions
            ->andReturn(new FulfilledPromise($mockResponse));

        $results = $this->submissionService->logSubmissionsToExternalService($submissions);

        $this->assertCount(2, $results);
        $this->assertEquals('success', $results[0]['status']);
        $this->assertEquals('success', $results[1]['status']);
    }

    /** @test */
    public function it_logs_submission_failure_to_external_service()
    {
        $submissions = [
            ['assignment_id' => 1, 'content' => 'Submission 1']
        ];

        $mockException = Mockery::mock(RequestException::class);
        $mockException->shouldReceive('getMessage')->andReturn('Server Error');

        $this->clientMock->shouldReceive('postAsync')
            ->once()
            ->andReturn(new RejectedPromise($mockException));

        Log::shouldReceive('error')->once();

        $results = $this->submissionService->logSubmissionsToExternalService($submissions);

        $this->assertCount(1, $results);
        $this->assertEquals('error', $results[0]['status']);
        $this->assertEquals('Failed to log submission', $results[0]['message']);
    }
}
