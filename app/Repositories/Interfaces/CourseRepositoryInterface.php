<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface
{
    /**
     * Get all courses.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get a course by its ID.
     *
     * @param int $id
     * @return array|null
     */
    public function find($id);

    /**
     * Create a new course.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data);

    /**
     * Update an existing course.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data);

    /**
     * Delete a course.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);
}
