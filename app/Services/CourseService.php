<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\SubmissionService;

class CourseService
{
    protected $courseRepository;
    protected $submissionService;

    public function __construct(CourseRepositoryInterface $courseRepository, SubmissionService $submissionService)
    {
        $this->courseRepository = $courseRepository;
        $this->submissionService = $submissionService;
    }

    public function getAllCourses()
    {
        return $this->courseRepository->all();
    }

    public function getCourseById($id)
    {
        return $this->courseRepository->find($id);
    }

    public function createCourse(array $data)
    {
        $data['teacher_id'] = Auth::id();
        return $this->courseRepository->create($data);
    }

    public function updateCourse($id, array $data)
    {
        return $this->courseRepository->update($id, $data);
    }

    public function deleteCourse($id)
    {
        return $this->courseRepository->delete($id);
    }

    /**
     * Allows a student to join a course.
     *
     * @param int $userId
     * @param int $courseId
     * @return mixed
     */
    public function joinCourse($userId, $courseId)
    {
        $user = User::findOrFail($userId);

        $user->joinedCourses()->attach($courseId);

        return ['message' => 'Successfully joined the course'];
    }

}
