<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $model;

    public function __construct(Project $project)
    {
        $this->model = $project;
    }

    public function getDataWithTaskCount()
    {
        return $this->model->withCount('task')->get();
    }

    public function findProjectById($id)
    {
        return $this->model->find($id);
    }

    public function createProject(array $data)
    {
        return $this->model->create($data);
    }

    public function updateProject($id, array $data)
    {
        $project = $this->findProjectById($id);
        if ($project) {
            $project->update($data);
        }
        return $project;
    }

    public function deleteProject($id)
    {
        $project = $this->findProjectById($id);
        if ($project) {
            $project->delete();
            return true;
        }
        return false;
    }
}