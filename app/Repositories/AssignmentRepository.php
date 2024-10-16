<?php
namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Assignment::all();
    }

    public function find($id): Assignment
    {
        return Assignment::findOrFail($id);
    }

    public function create(array $data): Assignment
    {
        return Assignment::create($data);
    }

    public function update($id, array $data): ?Assignment
    {
        try {
            $assignment = $this->find($id);
            $assignment->update($data);
            return $assignment;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function delete($id): bool
    {
        try {
            $assignment = $this->find($id);
            return $assignment->delete();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
