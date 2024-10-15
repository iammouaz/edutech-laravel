<?php
namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function all()
    {
        return Assignment::all();
    }

    public function find($id)
    {
        return Assignment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Assignment::create($data);
    }

    public function update($id, array $data)
    {
        $assignment = $this->find($id);
        $assignment->update($data);
        return $assignment;
    }

    public function delete($id)
    {
        $assignment = $this->find($id);
        $assignment->delete();
    }
}
