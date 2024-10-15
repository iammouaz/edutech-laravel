<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CourseService;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\SubmissionService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery;

class CourseServiceTest extends TestCase
{
    protected $courseService;
    protected $courseRepositoryMock;
    protected $submissionServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Mockery\LegacyMockInterface&\Mockery\MockInterface|CourseRepositoryInterface $courseRepositoryMock */
        $this->courseRepositoryMock = Mockery::mock(CourseRepositoryInterface::class);

        /** @var \Mockery\LegacyMockInterface&\Mockery\MockInterface|SubmissionService $submissionServiceMock */
        $this->submissionServiceMock = Mockery::mock(SubmissionService::class);

        $this->courseService = new CourseService($this->courseRepositoryMock, $this->submissionServiceMock);
    }

    /** @test */
    public function it_can_get_all_courses()
    {
        $this->courseRepositoryMock
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect(['course1', 'course2']));

        $result = $this->courseService->getAllCourses();

        $this->assertCount(2, $result);
        $this->assertEquals(['course1', 'course2'], $result->toArray());
    }

    /** @test */
    public function it_can_get_course_by_id()
    {
        $courseId = 1;
        $this->courseRepositoryMock
            ->shouldReceive('find')
            ->with($courseId)
            ->once()
            ->andReturn(['id' => 1, 'title' => 'Course 1']);

        $result = $this->courseService->getCourseById($courseId);

        $this->assertEquals(['id' => 1, 'title' => 'Course 1'], $result);
    }

    /** @test */
    public function it_can_create_a_course()
    {
        Auth::shouldReceive('id')->once()->andReturn(1);

        $courseData = ['title' => 'New Course', 'description' => 'Course description'];
        $this->courseRepositoryMock
            ->shouldReceive('create')
            ->with(array_merge($courseData, ['teacher_id' => 1]))
            ->once()
            ->andReturn(['id' => 1, 'title' => 'New Course']);

        $result = $this->courseService->createCourse($courseData);

        $this->assertEquals(['id' => 1, 'title' => 'New Course'], $result);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        $courseId = 1;
        $courseData = ['title' => 'Updated Course'];
        $this->courseRepositoryMock
            ->shouldReceive('update')
            ->with($courseId, $courseData)
            ->once()
            ->andReturn(true);

        $result = $this->courseService->updateCourse($courseId, $courseData);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_delete_a_course()
    {
        $courseId = 1;
        $this->courseRepositoryMock
            ->shouldReceive('delete')
            ->with($courseId)
            ->once()
            ->andReturn(true);

        $result = $this->courseService->deleteCourse($courseId);

        $this->assertTrue($result);
    }

}
