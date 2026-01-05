<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getProjectCounts();
    public function getTaskCounts();
    public function getMonthlyDoneTaskCounts();
    public function getLatestTasks($limit = 5);
}