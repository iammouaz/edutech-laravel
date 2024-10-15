<?php

namespace App\Repositories\Interfaces;

interface AssignmentRepositoryInterface
{
    /**
     * Get all assignments.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get an assignment by its ID.
     *
     * @param int $id
     * @return array|null
     */
    public function find($id);

    /**
     * Create a new assignment.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data);

    /**
     * Update an existing assignment.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data);

    /**
     * Delete an assignment.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);
}
