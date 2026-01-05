<?php

namespace App\Repositories;

interface ProjectRepositoryInterface
{
    public function getDataWithTaskCount();
    public function findProjectById($id);
    public function createProject(array $data);
    public function updateProject($id, array $data);
    public function deleteProject($id);
}