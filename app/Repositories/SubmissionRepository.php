<?php

namespace App\Repositories;

use App\Models\Submission;
use App\Repositories\Interfaces\SubmissionRepositoryInterface;

class SubmissionRepository implements SubmissionRepositoryInterface
{
    public function all()
    {
        return Submission::all();
    }

    public function find($id)
    {
        return Submission::findOrFail($id);
    }

    public function create(array $data)
    {
        return Submission::create($data);
    }

    public function update($id, array $data)
    {
        $submission = $this->find($id);
        $submission->update($data);
        return $submission;
    }

    public function delete($id)
    {
        $submission = $this->find($id);
        $submission->delete();
    }
}
