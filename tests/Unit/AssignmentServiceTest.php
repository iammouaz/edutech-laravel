<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Services\AssignmentService;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;

class AssignmentServiceTest extends TestCase
{
    protected $assignmentService;
    protected $assignmentRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assignmentRepositoryMock = Mockery::mock(AssignmentRepositoryInterface::class);

        $this->assignmentService = new AssignmentService($this->assignmentRepositoryMock);
    }

    /** @test */
    public function it_can_get_all_assignments()
    {
        $this->assignmentRepositoryMock
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect(['assignment1', 'assignment2']));

        $result = $this->assignmentService->getAllAssignments();

        $this->assertCount(2, $result);
        $this->assertEquals(['assignment1', 'assignment2'], $result->toArray());
    }

    /** @test */
    public function it_can_get_assignment_by_id()
    {
        $assignmentId = 1;
        $this->assignmentRepositoryMock
            ->shouldReceive('find')
            ->with($assignmentId)
            ->once()
            ->andReturn(['id' => 1, 'title' => 'Assignment 1']);

        $result = $this->assignmentService->getAssignmentById($assignmentId);

        $this->assertEquals(['id' => 1, 'title' => 'Assignment 1'], $result);
    }

    /** @test */
    public function it_can_create_an_assignment()
    {
        $assignmentData = ['title' => 'New Assignment', 'description' => 'Assignment description'];

        $this->assignmentRepositoryMock
            ->shouldReceive('create')
            ->with($assignmentData)
            ->once()
            ->andReturn(['id' => 1, 'title' => 'New Assignment']);

        $result = $this->assignmentService->createAssignment($assignmentData);

        $this->assertEquals(['id' => 1, 'title' => 'New Assignment'], $result);
    }

    /** @test */
    public function it_can_update_an_assignment()
    {
        $assignmentId = 1;
        $assignmentData = ['title' => 'Updated Assignment'];

        $this->assignmentRepositoryMock
            ->shouldReceive('update')
            ->with($assignmentId, $assignmentData)
            ->once()
            ->andReturn(true);

        $result = $this->assignmentService->updateAssignment($assignmentId, $assignmentData);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_delete_an_assignment()
    {
        $assignmentId = 1;

        $this->assignmentRepositoryMock
            ->shouldReceive('delete')
            ->with($assignmentId)
            ->once()
            ->andReturn(true);

        $result = $this->assignmentService->deleteAssignment($assignmentId);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
