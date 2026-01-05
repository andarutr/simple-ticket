<?php

namespace App\Repositories;

interface TaskRepositoryInterface
{
    public function getDataWithProjectAndCounts();
    public function getTaskSummary();
    public function getAllProjects();
    public function findTaskById($id);
    public function findTaskWithProject($id);
    public function createTask(array $data);
    public function updateTask($id, array $data);
    public function deleteTask($id);
}